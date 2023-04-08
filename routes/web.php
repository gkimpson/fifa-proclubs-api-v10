<?php

declare(strict_types=1);

use App\Http\Controllers\ClubController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HighchartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResultController;
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

Route::get('results', [ResultController::class, 'index'])->name('results.index');

Route::prefix('club/{clubId}/platform/{platform}')->group(function () {
    Route::get('/', [ClubController::class, 'index'])->name('club.index');
    Route::get('career', [ClubController::class, 'career'])->name('club.career');
    Route::get('cup', [ClubController::class, 'cup'])->name('club.cup');
    Route::get('form', [ClubController::class, 'form'])->name('club.form');
    Route::get('league', [ClubController::class, 'league'])->name('club.league');
    Route::get('members', [ClubController::class, 'members'])->name('club.members');
    Route::get('players/{player}', [ClubController::class, 'player'])->name('club.players');
    Route::get('search', [ClubController::class, 'search'])->name('club.search');
    Route::get('season', [ClubController::class, 'season'])->name('club.season');
    Route::get('settings', [ClubController::class, 'settings'])->name('club.settings');
    Route::get('squad', [ClubController::class, 'squad'])->name('club.squad');
    Route::get('squad/compare/{player1}/{player2}', [ClubController::class, 'compare'])->name('club.squad.compare');
    Route::get('squad/ranking', [ClubController::class, 'ranking'])->name('club.squad.ranking');
});

Route::get('platform/{platform}/leaderboard/{leaderboardType}/', [ClubController::class, 'leaderboard'])->name('club.leaderboard');

Route::prefix('chart')->group(function () {
    Route::get('/', [HighchartController::class, 'index'])->name('chart.index');
});

Route::get('debug', [ClubController::class, 'debug'])->name('club.debug');

// playing around with Blueprint autogenerator
Route::resource('video', App\Http\Controllers\VideoController::class)->only('index', 'show');

require __DIR__ . '/auth.php';
