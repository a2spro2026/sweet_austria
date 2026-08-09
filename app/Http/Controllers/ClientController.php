<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(): JsonResponse
    {
        $clients = Client::orderBy('code')->get()->map(fn (Client $c) => $this->format($c));

        return response()->json([
            'clients' => $clients,
            'next_id' => $this->nextCode(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);

        $client = Client::create([
            'code' => $this->nextCode(),
            ...$data,
        ]);

        return response()->json([
            'message' => 'Client enregistré',
            'client' => $this->format($client),
        ], 201);
    }

    public function update(Request $request, string $code): JsonResponse
    {
        $client = Client::where('code', $code)->firstOrFail();
        $client->update($this->validated($request));

        return response()->json([
            'message' => 'Client modifié',
            'client' => $this->format($client->fresh()),
        ]);
    }

    public function destroy(string $code): JsonResponse
    {
        $client = Client::where('code', $code)->firstOrFail();
        $client->delete();

        return response()->json(['message' => 'Client supprimé']);
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

    private function format(Client $c): array
    {
        return [
            'id' => $c->code,
            'nom' => $c->nom,
            'type' => $c->type,
            'ville' => $c->ville,
            'adresse' => $c->adresse,
            'telephone' => $c->telephone,
            'fixe' => $c->fixe,
            'email' => $c->email,
            'statut' => $c->statut,
            'type_paiement' => $c->type_paiement,
            'banque' => $c->banque,
            'rib' => $c->rib,
            'solde' => (float) $c->solde,
        ];
    }

    private function nextCode(): string
    {
        $max = Client::pluck('code')
            ->map(fn (string $c) => (int) substr($c, 2))
            ->max() ?? 0;

        return 'CL' . str_pad((string) ($max + 1), 4, '0', STR_PAD_LEFT);
    }
}
