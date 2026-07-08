<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'ref',
        'designation',
        'type',
        'categorie',
        'famille',
        'quantite',
        'unite',
        'prix_achat',
        'prix_vente',
        'photo',
    ];

    protected function casts(): array
    {
        return [
            'quantite' => 'decimal:3',
            'prix_achat' => 'decimal:2',
            'prix_vente' => 'decimal:2',
        ];
    }
}
