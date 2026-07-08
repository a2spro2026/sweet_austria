<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function index(): JsonResponse
    {
        $produits = Produit::orderBy('ref')->get()->map(fn (Produit $p) => $this->format($p));

        return response()->json([
            'produits' => $produits,
            'next_ref' => $this->nextRef(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);

        $produit = Produit::create([
            'ref' => $this->nextRef(),
            ...$data,
        ]);

        if ($request->hasFile('photo')) {
            $produit->update([
                'photo' => $this->storePhoto($request->file('photo'), $produit->ref),
            ]);
        }

        return response()->json([
            'message' => 'Produit enregistré',
            'produit' => $this->format($produit->fresh()),
        ], 201);
    }

    public function update(Request $request, string $ref): JsonResponse
    {
        $produit = Produit::where('ref', $ref)->firstOrFail();
        $data = $this->validated($request);

        if ($request->boolean('remove_photo')) {
            $this->deletePhoto($produit->photo);
            $data['photo'] = null;
        }

        if ($request->hasFile('photo')) {
            $this->deletePhoto($produit->photo);
            $data['photo'] = $this->storePhoto($request->file('photo'), $produit->ref);
        }

        $produit->update($data);

        return response()->json([
            'message' => 'Produit modifié',
            'produit' => $this->format($produit->fresh()),
        ]);
    }

    public function destroy(string $ref): JsonResponse
    {
        $produit = Produit::where('ref', $ref)->firstOrFail();
        $this->deletePhoto($produit->photo);
        $produit->delete();

        return response()->json(['message' => 'Produit supprimé']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'designation' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50', 'in:Pro Cru,Pro Fini,Pro Div'],
            'categorie' => ['nullable', 'string', 'max:255'],
            'famille' => ['nullable', 'string', 'max:255'],
            'quantite' => ['nullable', 'numeric', 'min:0'],
            'unite' => ['nullable', 'string', 'max:10'],
            'prix_achat' => ['nullable', 'numeric', 'min:0'],
            'prix_vente' => ['nullable', 'numeric', 'min:0'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        unset($data['photo'], $data['remove_photo']);

        return $data;
    }

    private function format(Produit $p): array
    {
        return [
            'ref' => $p->ref,
            'designation' => $p->designation,
            'type' => $p->type,
            'categorie' => $p->categorie,
            'famille' => $p->famille,
            'quantite' => (float) $p->quantite,
            'unite' => $p->unite,
            'prix_achat' => $p->prix_achat !== null ? (float) $p->prix_achat : null,
            'prix_vente' => $p->prix_vente !== null ? (float) $p->prix_vente : null,
            'photo' => $p->photo,
            'photo_url' => $p->photo ? '/storage/' . ltrim(str_replace('\\', '/', $p->photo), '/') : null,
        ];
    }

    private function storePhoto($file, string $ref): string
    {
        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = $ref . '_' . time() . '.' . strtolower($ext);

        return $file->storeAs('produits', $filename, 'public');
    }

    private function deletePhoto(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function nextRef(): string
    {
        $max = Produit::pluck('ref')
            ->map(fn (string $r) => (int) substr($r, 2))
            ->max() ?? 0;

        return 'PR' . str_pad((string) ($max + 1), 4, '0', STR_PAD_LEFT);
    }
}
