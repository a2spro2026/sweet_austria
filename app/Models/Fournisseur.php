<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $fillable = [
        'code',
        'nom',
        'type',
        'ville',
        'adresse',
        'telephone',
        'fixe',
        'email',
        'statut',
        'type_paiement',
        'banque',
        'rib',
        'solde',
    ];

    protected function casts(): array
    {
        return [
            'solde' => 'decimal:2',
        ];
    }
}
