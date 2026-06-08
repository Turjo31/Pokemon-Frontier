<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokedexController;

Route::get('/login',    fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');

Route::get('/pokedex',  [PokedexController::class, 'index'])->name('pokedex');

Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');