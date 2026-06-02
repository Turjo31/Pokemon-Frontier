<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokedexController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/pokedex', [PokedexController::class, 'index']);