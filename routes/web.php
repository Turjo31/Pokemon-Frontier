<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokedexController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\Admin\AdminPokemonController;
use App\Http\Controllers\Admin\AdminLeagueController;

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('home');

// Auth
Route::get('/login',    [App\Http\Controllers\Auth\LoginController::class,    'showForm'])->name('login');
Route::post('/login',   [App\Http\Controllers\Auth\LoginController::class,    'login']);
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showForm'])->name('register');
Route::post('/register',[App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('/logout',  [App\Http\Controllers\Auth\LoginController::class,    'logout'])->name('logout');

// Trainer routes
Route::middleware('auth:trainer')->group(function () {
    Route::get('/dashboard',   [DashboardController::class,  'index'])->name('dashboard');
    Route::get('/pokedex',     [PokedexController::class,    'index'])->name('pokedex');

    Route::get('/teams',          [TeamController::class, 'index'])->name('teams.index');
    Route::post('/teams',         [TeamController::class, 'store'])->name('teams.store');
    Route::delete('/teams/{id}',  [TeamController::class, 'destroy'])->name('teams.destroy');

    Route::get('/tournaments',              [TournamentController::class, 'index'])->name('tournaments.index');
    Route::post('/tournaments/{id}/register', [TournamentController::class, 'register'])->name('tournaments.register');

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
});

Route::get('/admin/login',  [App\Http\Controllers\Auth\AdminLoginController::class, 'showForm'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout',[App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('admin.logout');

// Admin routes
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pokemon',           [AdminPokemonController::class, 'index'])->name('pokemon.index');
    Route::post('/pokemon',          [AdminPokemonController::class, 'store'])->name('pokemon.store');
    Route::put('/pokemon/{id}',      [AdminPokemonController::class, 'update'])->name('pokemon.update');
    Route::delete('/pokemon/{id}',   [AdminPokemonController::class, 'destroy'])->name('pokemon.destroy');

    Route::get('/leagues',                      [AdminLeagueController::class, 'index'])->name('leagues.index');
    Route::post('/leagues',                     [AdminLeagueController::class, 'storeLeague'])->name('leagues.store');
    Route::put('/leagues/{id}',                 [AdminLeagueController::class, 'updateLeague'])->name('leagues.update');
    Route::delete('/leagues/{id}',              [AdminLeagueController::class, 'destroyLeague'])->name('leagues.destroy');

    Route::post('/tournaments',                 [AdminLeagueController::class, 'storeTournament'])->name('tournaments.store');
    Route::put('/tournaments/{id}',             [AdminLeagueController::class, 'updateTournament'])->name('tournaments.update');
    Route::delete('/tournaments/{id}',          [AdminLeagueController::class, 'destroyTournament'])->name('tournaments.destroy');
    Route::post('/tournaments/{id}/simulate',   [AdminLeagueController::class, 'simulate'])->name('tournaments.simulate');
});