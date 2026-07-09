<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokedexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamController;




Route::get('/',             fn() => view('welcome'))->name('home');
Route::get('/login',  [App\Http\Controllers\Auth\LoginController::class,    'showForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class,    'login']);
Route::get('/register',  [App\Http\Controllers\Auth\RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('/logout',   [App\Http\Controllers\Auth\LoginController::class,    'logout'])->name('logout');

Route::get('/pokedex',  [PokedexController::class, 'index'])->name('pokedex');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/tournaments', fn() => view('tournaments', ['tournaments' => [], 'teams' => []]))->name('tournaments.index');

Route::get('/teams',         [TeamController::class, 'index'])->name('teams.index');
Route::post('/teams',        [TeamController::class, 'store'])->name('teams.store');
Route::delete('/teams/{id}', [TeamController::class, 'destroy'])->name('teams.destroy');

Route::get('/leaderboard',  fn() => view('leaderboard', ['rankings' => [], 'leagues' => [], 'recentForm' => [], 'myRank' => null, 'myPoints' => 0, 'myWins' => 0, 'myLosses' => 0, 'totalMatches' => 0]))->name('leaderboard');


Route::get('/admin/pokemon', fn() => view('admin.pokemon', ['pokemon' => []]))->name('admin.pokemon.index');
Route::get('/admin/leagues', fn() => view('admin.leagues', ['leagues' => [], 'tournaments' => []]))->name('admin.leagues.index');