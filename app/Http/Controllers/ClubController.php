<?php

namespace App\Http\Controllers;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use App\Services\ProClubsApiService;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index(int $clubId, string $platform)
    {
        return ProClubsApiService::clubsInfo(Platforms::getPlatform($platform), $clubId);
    }

    public function members(int $clubId, string $platform)
    {
        return ProClubsApiService::memberStats(Platforms::getPlatform($platform), $clubId);
    }

    public function career(int $clubId, string $platform)
    {
        return ProClubsApiService::careerStats(Platforms::getPlatform($platform), $clubId);
    }

    public function season(int $clubId, string $platform)
    {
        return ProClubsApiService::seasonStats(Platforms::getPlatform($platform), $clubId);
    }

    public function settings(string $clubName, string $platform)
    {
        return ProClubsApiService::settings(Platforms::getPlatform($platform), $clubName);
    }

    public function search(string $clubName, string $platform)
    {
        return ProClubsApiService::search(Platforms::getPlatform($platform), $clubName);
    }

    public function league(int $clubId, string $platform)
    {
        return ProClubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::LEAGUE);
    }

    public function cup(int $clubId, string $platform)
    {
        return ProClubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::CUP);
    }

    public function leaderboard(string $platform, string $leaderboardType)
    {
        return ProClubsApiService::leaderboard(Platforms::getPlatform($platform), $leaderboardType);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
