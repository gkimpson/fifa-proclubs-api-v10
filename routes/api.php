<?php

use App\Http\Controllers\Api\V1\Auth;
use App\Http\Controllers\Api\V1\ClubController;
use App\Http\Controllers\Api\V1\ResultController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', Auth\RegisterController::class);

Route::get('results', [ResultController::class, 'index']);
Route::get('results/{result}', [ResultController::class, 'show']);

Route::prefix('club/{clubId}/platform/{platform}')->group(function () {
    Route::get('/', [ClubController::class, 'index'])->name('api.club.index');
    Route::get('career', [ClubController::class, 'career'])->name('api.club.career');
    Route::get('cup', [ClubController::class, 'cup'])->name('api.club.cup');
    Route::get('form', [ClubController::class, 'form'])->name('api.club.form');
    Route::get('league', [ClubController::class, 'league'])->name('api.club.league');
    Route::get('members', [ClubController::class, 'members'])->name('api.club.members');
    Route::get('players/{player}', [ClubController::class, 'player'])->name('api.club.players');
    Route::get('search', [ClubController::class, 'search'])->name('api.club.search');
    Route::get('season', [ClubController::class, 'season'])->name('api.club.season');
    Route::get('settings', [ClubController::class, 'settings'])->name('api.club.settings');
    Route::get('squad', [ClubController::class, 'squad'])->name('api.club.squad');
    Route::get('squad/compare/{player1}/{player2}', [ClubController::class, 'compare'])->name('api.club.squad.compare');
    Route::get('squad/ranking', [ClubController::class, 'ranking'])->name('api.club.squad.ranking');
});

Route::get('platform/{platform}/leaderboard/{leaderboardType}/', [ClubController::class, 'leaderboard'])->name('api.club.leaderboard');
