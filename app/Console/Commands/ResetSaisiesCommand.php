<?php

namespace App\Console\Commands;

use App\Models\Fournisseur;
use App\Models\Produit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ResetSaisiesCommand extends Command
{
    protected $signature = 'app:reset-saisies';

    protected $description = 'Vide toutes les données saisies (fournisseurs, produits, photos)';

    public function handle(): int
    {
        Produit::query()->each(function (Produit $produit) {
            if ($produit->photo && Storage::disk('public')->exists($produit->photo)) {
                Storage::disk('public')->delete($produit->photo);
            }
        });

        $produits = Produit::query()->delete();
        $fournisseurs = Fournisseur::query()->delete();

        $this->info("Supprimé : {$fournisseurs} fournisseur(s), {$produits} produit(s).");
        $this->info('Les unités de mesure de référence sont conservées.');
        $this->info('Rechargez l\'application dans le navigateur pour repartir à zéro.');

        return self::SUCCESS;
    }
}
