<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokedexController;


Route::get('/',             fn() => view('welcome'))->name('home');
Route::get('/login',    fn() => view('auth.login'))->name('login');
Route::get('/register', fn() => view('auth.register'))->name('register');

Route::get('/pokedex',  [PokedexController::class, 'index'])->name('pokedex');
Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
Route::get('/teams', fn() => view('teams', ['pokemon' => []]))->name('teams.index');
Route::get('/tournaments', fn() => view('tournaments', ['tournaments' => [], 'teams' => []]))->name('tournaments.index');

Route::get('/leaderboard',  fn() => view('leaderboard', ['rankings' => [], 'leagues' => [], 'recentForm' => [], 'myRank' => null, 'myPoints' => 0, 'myWins' => 0, 'myLosses' => 0, 'totalMatches' => 0]))->name('leaderboard');