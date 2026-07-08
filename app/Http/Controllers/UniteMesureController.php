<?php

namespace App\Http\Controllers;

use App\Models\UniteMesure;
use Illuminate\Http\JsonResponse;

class UniteMesureController extends Controller
{
    public function index(): JsonResponse
    {
        $unites = UniteMesure::where('actif', true)
            ->orderBy('libelle')
            ->get()
            ->map(fn (UniteMesure $u) => [
                'code' => $u->code,
                'libelle' => $u->libelle,
            ]);

        return response()->json(['unites' => $unites]);
    }
}
