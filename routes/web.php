<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\UniteMesureController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/api/fournisseurs', [FournisseurController::class, 'index']);
Route::post('/api/fournisseurs', [FournisseurController::class, 'store']);
Route::put('/api/fournisseurs/{code}', [FournisseurController::class, 'update']);
Route::delete('/api/fournisseurs/{code}', [FournisseurController::class, 'destroy']);

Route::get('/api/clients', [ClientController::class, 'index']);
Route::post('/api/clients', [ClientController::class, 'store']);
Route::put('/api/clients/{code}', [ClientController::class, 'update']);
Route::delete('/api/clients/{code}', [ClientController::class, 'destroy']);

Route::get('/api/unites-mesure', [UniteMesureController::class, 'index']);

Route::get('/api/produits', [ProduitController::class, 'index']);
Route::post('/api/produits', [ProduitController::class, 'store']);
Route::put('/api/produits/{ref}', [ProduitController::class, 'update']);
Route::delete('/api/produits/{ref}', [ProduitController::class, 'destroy']);
