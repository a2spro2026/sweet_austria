<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unites_mesure', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('libelle');
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        DB::table('unites_mesure')->insert([
            ['code' => 'KG', 'libelle' => 'Kilogramme', 'actif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'G', 'libelle' => 'Gramme', 'actif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'L', 'libelle' => 'Litre', 'actif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'ML', 'libelle' => 'Millilitre', 'actif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'UN', 'libelle' => 'Unité', 'actif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CRT', 'libelle' => 'Carton', 'actif' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'SAC', 'libelle' => 'Sac', 'actif' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('unites_mesure');
    }
};
