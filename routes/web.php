<?php

declare(strict_types=1);

use App\Http\Controllers\ClubController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HighchartController;
use App\Http\Controllers\PlayerController;
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
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('search', [SearchController::class, 'index'])->name('search.index');
Route::get('search/submit', [SearchController::class, 'submit'])->name('search.submit');

Route::prefix('club/{clubId}/platform/{platform}')->group(function () {
    Route::get('/', [ClubController::class, 'index'])->name('club.index');
    Route::get('career', [ClubController::class, 'career'])->name('club.career');
    Route::get('cup', [ClubController::class, 'cup'])->name('club.cup');
    Route::get('form', [ClubController::class, 'form'])->name('club.form');
    Route::get('league', [ClubController::class, 'league'])->name('club.league');
    Route::get('members', [ClubController::class, 'members'])->name('club.members');
    Route::get('players/{player}', [ClubController::class, 'player'])->name('club.players');
    Route::get('search/{clubName}', [ClubController::class, 'search'])->name('club.search');
    Route::get('season', [ClubController::class, 'season'])->name('club.season');
    Route::get('settings', [ClubController::class, 'settings'])->name('club.settings');
    Route::get('squad', [ClubController::class, 'squad'])->name('club.squad');
    Route::get('squad/compare/all', [ClubController::class, 'compareAll'])->name('club.squad.compare.all');
    Route::get('squad/compare/{player1}/{player2}', [ClubController::class, 'compare'])->name('club.squad.compare');
    Route::get('squad/ranking', [ClubController::class, 'ranking'])->name('club.squad.ranking');
});
Route::prefix('chart')->group(function () {
    Route::get('/', [HighchartController::class, 'index'])->name('chart.index');
});
Route::prefix('player')->group(function () {
    Route::get('search', [PlayerController::class, 'search'])->name('player.search');
});

// some routes that don't necessarily fit into a grouping
Route::get('platform/{platform}/leaderboard/{leaderboardType}/', [ClubController::class, 'leaderboard'])->name('club.leaderboard');
Route::get('results', [ResultController::class, 'index'])->name('results.index');

// playing around with Blueprint autogenerator
Route::resource('video', App\Http\Controllers\VideoController::class)->only('index', 'show');

require __DIR__ . '/auth.php';
