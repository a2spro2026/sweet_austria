<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('ref', 20)->unique();
            $table->string('designation');
            $table->string('categorie')->nullable();
            $table->string('famille')->nullable();
            $table->decimal('quantite', 14, 3)->default(0);
            $table->string('unite', 10)->nullable();
            $table->decimal('prix_achat', 14, 2)->nullable();
            $table->decimal('prix_vente', 14, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
