<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniteMesure extends Model
{
    protected $table = 'unites_mesure';

    protected $fillable = [
        'code',
        'libelle',
        'actif',
    ];

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
        ];
    }
}
