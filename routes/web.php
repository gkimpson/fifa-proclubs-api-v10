<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/results', [ResultController::class, 'index'])->name('results.index');

Route::prefix('club')->group(function () {
    Route::get('/{clubId}/platform/{platform}', [ClubController::class, 'index'])->name('club.index');
    Route::get('/{clubId}/members/platform/{platform}', [ClubController::class, 'members'])->name('club.members');
    Route::get('/{clubId}/career/platform/{platform}', [ClubController::class, 'career'])->name('club.career');
    Route::get('/{clubId}/season/platform/{platform}', [ClubController::class, 'season'])->name('club.season');
    Route::get('/{clubName}/settings/platform/{platform}', [ClubController::class, 'settings'])->name('club.settings');
    Route::get('/{clubName}/search/platform/{platform}', [ClubController::class, 'search'])->name('club.search');
    Route::get('/{clubId}/matches/league/platform/{platform}', [ClubController::class, 'league'])->name('club.matchesleague');
    Route::get('/{clubId}/matches/cup/platform/{platform}', [ClubController::class, 'cup'])->name('club.matchescup');
    // todo - additional routes below
    Route::get('/{clubId}/matches/form/platform/{platform}', [ClubController::class, 'form'])->name('club.form');
    Route::get('/{clubId}/matches/rankings/platform/{platform}', [ClubController::class, 'rankings'])->name('club.rankings');
    Route::get('/{clubId}/matches/squad/platform/{platform}', [ClubController::class, 'squad'])->name('club.squad');
    Route::get('/leaderboard/platform/{platform}/{leaderboardType}', [ClubController::class, 'leaderboard'])->name('club.leaderboard');
});
require __DIR__.'/auth.php';
