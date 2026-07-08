<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index(): JsonResponse
    {
        $fournisseurs = Fournisseur::orderBy('code')->get()->map(fn (Fournisseur $f) => $this->format($f));

        return response()->json([
            'fournisseurs' => $fournisseurs,
            'next_id' => $this->nextCode(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);

        $fournisseur = Fournisseur::create([
            'code' => $this->nextCode(),
            ...$data,
        ]);

        return response()->json([
            'message' => 'Fournisseur enregistré',
            'fournisseur' => $this->format($fournisseur),
        ], 201);
    }

    public function update(Request $request, string $code): JsonResponse
    {
        $fournisseur = Fournisseur::where('code', $code)->firstOrFail();
        $fournisseur->update($this->validated($request));

        return response()->json([
            'message' => 'Fournisseur modifié',
            'fournisseur' => $this->format($fournisseur->fresh()),
        ]);
    }

    public function destroy(string $code): JsonResponse
    {
        $fournisseur = Fournisseur::where('code', $code)->firstOrFail();
        $fournisseur->delete();

        return response()->json(['message' => 'Fournisseur supprimé']);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:10'],
            'ville' => ['nullable', 'string', 'max:255'],
            'adresse' => ['nullable', 'string'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'fixe' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'string', 'max:255'],
            'statut' => ['nullable', 'string', 'max:10'],
            'type_paiement' => ['nullable', 'string', 'max:10'],
            'banque' => ['nullable', 'string', 'max:255'],
            'rib' => ['nullable', 'string', 'max:50'],
            'solde' => ['nullable', 'numeric'],
        ]);
    }

    private function format(Fournisseur $f): array
    {
        return [
            'id' => $f->code,
            'nom' => $f->nom,
            'type' => $f->type,
            'ville' => $f->ville,
            'adresse' => $f->adresse,
            'telephone' => $f->telephone,
            'fixe' => $f->fixe,
            'email' => $f->email,
            'statut' => $f->statut,
            'type_paiement' => $f->type_paiement,
            'banque' => $f->banque,
            'rib' => $f->rib,
            'solde' => (float) $f->solde,
        ];
    }

    private function nextCode(): string
    {
        $max = Fournisseur::pluck('code')
            ->map(fn (string $c) => (int) substr($c, 2))
            ->max() ?? 0;

        return 'FR' . str_pad((string) ($max + 1), 4, '0', STR_PAD_LEFT);
    }
}
