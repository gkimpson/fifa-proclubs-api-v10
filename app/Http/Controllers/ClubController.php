<?php

namespace App\Http\Controllers;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use App\Services\ProclubsApiService;
use App\Services\ResultService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public int $clubId;
    public int $clubIds;
    public string $platform;
    public string $player1;
    public string $player2;

    public function __construct(Request $request)
    {
        $this->clubId = (int) $request->route('clubId') ?? 0;
        $this->clubIds = (int) $request->route('clubIds') ?? 0;
        $this->platform = (string) $request->route('platform') ?? '';
        $this->player1 = (string) $request->route('player1') ?? '';
        $this->player2 = (string) $request->route('player2') ?? '';
    }

    public function index(int $clubId, string $platform)
    {
        return ProclubsApiService::clubsInfo(Platforms::getPlatform($platform), $clubId);
    }

    public function members(int $clubId, string $platform)
    {
        return ProclubsApiService::memberStats(Platforms::getPlatform($platform), $clubId);
    }

    public function career(int $clubId, string $platform)
    {
        return ProclubsApiService::careerStats(Platforms::getPlatform($platform), $clubId);
    }

    public function season(int $clubId, string $platform)
    {
        return ProclubsApiService::seasonStats(Platforms::getPlatform($platform), $clubId);
    }

    public function settings(string $clubName, string $platform)
    {
        return ProclubsApiService::settings(Platforms::getPlatform($platform), $clubName);
    }

    public function search(string $clubName, string $platform)
    {
        return ProclubsApiService::search(Platforms::getPlatform($platform), $clubName);
    }

    public function league(int $clubId, string $platform, ResultService $resultService)
    {
        return ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::LEAGUE);
    }

    public function leaderboard(string $platform, string $leaderboardType)
    {
        return ProclubsApiService::leaderboard(Platforms::getPlatform($platform), $leaderboardType);
    }

    public function cup(int $clubId, string $platform)
    {
        return ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::CUP);
    }

    public function player(int $clubId, string $platform, string $playerName)
    {
        return ProclubsApiService::playerStats(Platforms::getPlatform($platform), $clubId, $playerName);
    }

    public function squad(ResultService $resultService)
    {
        $data = [
            'squad' => $resultService->getCachedData($this->clubId, $this->platform, 'squad'),
        ];

        return $data;
//        return view('club.squad', $data);
    }

    public function compare(ResultService $resultService)
    {
        $data = $resultService->getPlayerComparisonData($this->clubId, $this->platform, $this->player1, $this->player2);

        return $data;
    }

    public function ranking(ResultService $resultService)
    {
        $data = [
            'rankings' => $resultService->getRankingData($this->clubId, $this->platform),
            'perMatchRankings' => $resultService->getCustomRankingData($this->clubId, $this->platform),
        ];

        return $data;
//        return view('club.rankings', $data);
    }

    public function form(ResultService $resultService)
    {
//        Accept the parameters from routes or other inputs
//        Call some logic classes/methods, passing those parameters
//        Return the result: view, redirect, JSON return, etc.
    }
}
