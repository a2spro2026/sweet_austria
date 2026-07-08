<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('nom');
            $table->string('type', 10)->nullable();
            $table->string('ville')->nullable();
            $table->text('adresse')->nullable();
            $table->string('telephone', 30)->nullable();
            $table->string('fixe', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('statut', 10)->nullable();
            $table->string('type_paiement', 10)->nullable();
            $table->string('banque')->nullable();
            $table->string('rib', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
