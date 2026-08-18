<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Fournisseur;
use App\Models\Produit;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedFournisseurs();
        $this->seedClients();
        $this->seedProduitsDivers();
    }

    private function seedFournisseurs(): void
    {
        $rows = [
            ['nom' => 'Atlas Fruits Secs', 'type' => 'Ste', 'ville' => 'Nador', 'adresse' => 'Zone industrielle, Nador', 'telephone' => '0536601101', 'email' => 'atlas@fruits.ma', 'statut' => 'Act', 'type_paiement' => 'Vir', 'banque' => 'Attijariwafa Bank', 'rib' => '007 810 0001234567890123 45'],
            ['nom' => 'Maghreb Packaging', 'type' => 'Ste', 'ville' => 'Tanger', 'adresse' => 'Zone franche, Tanger', 'telephone' => '0539352202', 'email' => 'contact@maghrebpack.ma', 'statut' => 'Act', 'type_paiement' => 'Chq', 'banque' => 'BMCE Bank Of Africa', 'rib' => '011 450 0009876543210987 21'],
            ['nom' => 'Sahara Import', 'type' => 'Rev', 'ville' => 'Casablanca', 'adresse' => 'Aïn Sebaâ, Casablanca', 'telephone' => '0522453303', 'email' => 'sahara@import.ma', 'statut' => 'Act', 'type_paiement' => 'Esp'],
            ['nom' => 'Oriental Dry Nuts', 'type' => 'Ste', 'ville' => 'Oujda', 'adresse' => 'Bd Mohammed V, Oujda', 'telephone' => '0536704404', 'email' => 'oriental@nuts.ma', 'statut' => 'Act', 'type_paiement' => 'Eff', 'banque' => 'Banque Populaire'],
            ['nom' => 'Méditerranée Supplies', 'type' => 'G/c', 'ville' => 'Rabat', 'adresse' => 'Hay Riad, Rabat', 'telephone' => '0537755505', 'email' => 'info@medsupplies.ma', 'statut' => 'Act', 'type_paiement' => 'Vir', 'banque' => 'CIH Bank'],
            ['nom' => 'Al Amal Grossiste', 'type' => 'Rev', 'ville' => 'Nador', 'adresse' => 'Marché central, Nador', 'telephone' => '0661122334', 'email' => 'alamal@grossiste.ma', 'statut' => 'Act', 'type_paiement' => 'Esp'],
            ['nom' => 'Pack Plus Maroc', 'type' => 'Ste', 'ville' => 'Fès', 'adresse' => 'Sidi Brahim, Fès', 'telephone' => '0535626606', 'email' => 'ventes@packplus.ma', 'statut' => 'Act', 'type_paiement' => 'Chq', 'banque' => 'Crédit Agricole'],
            ['nom' => 'Agro Nador SARL', 'type' => 'Mc', 'ville' => 'Nador', 'adresse' => 'Route de Selouane, Nador', 'telephone' => '0661987654', 'email' => 'agro@nador.ma', 'statut' => 'Act', 'type_paiement' => 'Vers'],
        ];

        foreach ($rows as $row) {
            if (Fournisseur::where('nom', $row['nom'])->exists()) {
                continue;
            }
            Fournisseur::create([
                'code' => $this->nextCode(Fournisseur::class, 'FR'),
                ...$row,
                'solde' => 0,
            ]);
        }
    }

    private function seedClients(): void
    {
        $rows = [
            ['nom' => 'Superette Al Massira', 'type' => 'Pc', 'ville' => 'Nador', 'adresse' => 'Hay Al Massira, Nador', 'telephone' => '0662010101', 'email' => 'massira@client.ma', 'statut' => 'Act', 'type_paiement' => 'Esp'],
            ['nom' => 'Épicerie Al Qods', 'type' => 'Pc', 'ville' => 'Nador', 'adresse' => 'Bd Al Qods, Nador', 'telephone' => '0662020202', 'statut' => 'Act', 'type_paiement' => 'Esp'],
            ['nom' => 'Hôtel Saidia Beach', 'type' => 'G/c', 'ville' => 'Saidia', 'adresse' => 'Corniche, Saidia', 'telephone' => '0536610303', 'email' => 'achat@saidiabeach.ma', 'statut' => 'Act', 'type_paiement' => 'Vir', 'banque' => 'Attijariwafa Bank'],
            ['nom' => 'Café Central', 'type' => 'Mc', 'ville' => 'Berkane', 'adresse' => 'Place centrale, Berkane', 'telephone' => '0536610404', 'statut' => 'Act', 'type_paiement' => 'Chq'],
            ['nom' => 'Marché Al Houda', 'type' => 'Rev', 'ville' => 'Oujda', 'adresse' => 'Marché municipal, Oujda', 'telephone' => '0662050505', 'statut' => 'Act', 'type_paiement' => 'Esp'],
            ['nom' => 'Boutique Gourmet Tanger', 'type' => 'Mc', 'ville' => 'Tanger', 'adresse' => 'Malabata, Tanger', 'telephone' => '0539350606', 'email' => 'gourmet@tanger.ma', 'statut' => 'Act', 'type_paiement' => 'Chq'],
            ['nom' => 'Restaurant Al Andalous', 'type' => 'Mc', 'ville' => 'Nador', 'adresse' => 'Corniche, Nador', 'telephone' => '0662070707', 'statut' => 'Act', 'type_paiement' => 'Esp'],
            ['nom' => 'Mini Market Atlas', 'type' => 'Pc', 'ville' => 'Beni Ensar', 'adresse' => 'Route Melilla, Beni Ensar', 'telephone' => '0662080808', 'statut' => 'Act', 'type_paiement' => 'Esp'],
            ['nom' => 'Coopérative Femmes Nador', 'type' => 'Ste', 'ville' => 'Nador', 'adresse' => 'Centre artisanal, Nador', 'telephone' => '0536600909', 'email' => 'coop@femmesnador.ma', 'statut' => 'Act', 'type_paiement' => 'Vir'],
            ['nom' => 'Distributeur Nord Est', 'type' => 'G/c', 'ville' => 'Al Hoceima', 'adresse' => 'Zone commerciale, Al Hoceima', 'telephone' => '0539801010', 'email' => 'nordest@distrib.ma', 'statut' => 'Act', 'type_paiement' => 'Eff', 'banque' => 'Banque Populaire'],
        ];

        foreach ($rows as $row) {
            if (Client::where('nom', $row['nom'])->exists()) {
                continue;
            }
            Client::create([
                'code' => $this->nextCode(Client::class, 'CL'),
                ...$row,
                'solde' => 0,
            ]);
        }
    }

    private function seedProduitsDivers(): void
    {
        $rows = [
            ['designation' => 'Sac kraft 1 kg', 'categorie' => 'Emballage', 'famille' => 'Sacs', 'unite' => 'UN', 'quantite' => 500, 'prix_achat' => 1.20, 'prix_vente' => 2.00],
            ['designation' => 'Sac kraft 500 g', 'categorie' => 'Emballage', 'famille' => 'Sacs', 'unite' => 'UN', 'quantite' => 800, 'prix_achat' => 0.80, 'prix_vente' => 1.40],
            ['designation' => 'Carton 24 unités', 'categorie' => 'Emballage', 'famille' => 'Cartons', 'unite' => 'CRT', 'quantite' => 120, 'prix_achat' => 6.50, 'prix_vente' => 9.80],
            ['designation' => 'Étiquette ronde or', 'categorie' => 'Conditionnement', 'famille' => 'Étiquettes', 'unite' => 'UN', 'quantite' => 2000, 'prix_achat' => 0.15, 'prix_vente' => 0.35],
            ['designation' => 'Ruban cadeau or 20 mm', 'categorie' => 'Conditionnement', 'famille' => 'Rubans', 'unite' => 'UN', 'quantite' => 80, 'prix_achat' => 12.00, 'prix_vente' => 18.50],
            ['designation' => 'Film étirable 45 cm', 'categorie' => 'Emballage', 'famille' => 'Films', 'unite' => 'UN', 'quantite' => 40, 'prix_achat' => 45.00, 'prix_vente' => 68.00],
            ['designation' => 'Pot verre 250 ml', 'categorie' => 'Conditionnement', 'famille' => 'Pots', 'unite' => 'UN', 'quantite' => 300, 'prix_achat' => 3.40, 'prix_vente' => 5.20],
            ['designation' => 'Couvercle pot 250 ml', 'categorie' => 'Conditionnement', 'famille' => 'Pots', 'unite' => 'UN', 'quantite' => 300, 'prix_achat' => 0.90, 'prix_vente' => 1.50],
            ['designation' => 'Barquette plastique 500 g', 'categorie' => 'Emballage', 'famille' => 'Barquettes', 'unite' => 'UN', 'quantite' => 450, 'prix_achat' => 0.70, 'prix_vente' => 1.20],
            ['designation' => 'Sac zip 250 g', 'categorie' => 'Emballage', 'famille' => 'Sacs', 'unite' => 'UN', 'quantite' => 600, 'prix_achat' => 0.55, 'prix_vente' => 0.95],
            ['designation' => 'Palette bois Europe', 'categorie' => 'Logistique', 'famille' => 'Palettes', 'unite' => 'UN', 'quantite' => 25, 'prix_achat' => 85.00, 'prix_vente' => 120.00],
            ['designation' => 'Scotch transparent 48 mm', 'categorie' => 'Emballage', 'famille' => 'Adhésifs', 'unite' => 'UN', 'quantite' => 90, 'prix_achat' => 8.50, 'prix_vente' => 13.00],
            ['designation' => 'Papier soie blanc', 'categorie' => 'Conditionnement', 'famille' => 'Papiers', 'unite' => 'UN', 'quantite' => 60, 'prix_achat' => 18.00, 'prix_vente' => 28.00],
            ['designation' => 'Sachet cellophane 20x30', 'categorie' => 'Emballage', 'famille' => 'Sachets', 'unite' => 'UN', 'quantite' => 1000, 'prix_achat' => 0.25, 'prix_vente' => 0.45],
            ['designation' => 'Boîte métallique luxe', 'categorie' => 'Conditionnement', 'famille' => 'Boîtes', 'unite' => 'UN', 'quantite' => 80, 'prix_achat' => 14.50, 'prix_vente' => 22.00],
            ['designation' => 'Pince à sachet', 'categorie' => 'Conditionnement', 'famille' => 'Accessoires', 'unite' => 'UN', 'quantite' => 200, 'prix_achat' => 0.40, 'prix_vente' => 0.80],
            ['designation' => 'Gants nitrile boîte 100', 'categorie' => 'Hygiène', 'famille' => 'Protection', 'unite' => 'UN', 'quantite' => 35, 'prix_achat' => 28.00, 'prix_vente' => 42.00],
            ['designation' => 'Balance portable 5 kg', 'categorie' => 'Matériel', 'famille' => 'Pesage', 'unite' => 'UN', 'quantite' => 8, 'prix_achat' => 220.00, 'prix_vente' => 340.00],
            ['designation' => 'Marqueur alimentaire', 'categorie' => 'Conditionnement', 'famille' => 'Marquage', 'unite' => 'UN', 'quantite' => 50, 'prix_achat' => 6.00, 'prix_vente' => 9.50],
            ['designation' => 'Filet oignon 5 kg', 'categorie' => 'Emballage', 'famille' => 'Filets', 'unite' => 'UN', 'quantite' => 150, 'prix_achat' => 1.10, 'prix_vente' => 1.90],
        ];

        foreach ($rows as $row) {
            if (Produit::where('designation', $row['designation'])->exists()) {
                continue;
            }
            Produit::create([
                'ref' => $this->nextCode(Produit::class, 'PR', 'ref'),
                'type' => 'Pro Div',
                ...$row,
            ]);
        }
    }

    private function nextCode(string $model, string $prefix, string $column = 'code'): string
    {
        $max = $model::pluck($column)
            ->map(fn (string $c) => (int) substr($c, strlen($prefix)))
            ->max() ?? 0;

        return $prefix . str_pad((string) ($max + 1), 4, '0', STR_PAD_LEFT);
    }
}
