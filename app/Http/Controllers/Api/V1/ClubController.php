<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use App\Http\Controllers\Controller;
use App\Services\ProclubsApiService;
use App\Services\ResultService;

class ClubController extends Controller
{
    public function index(int $clubId, string $platform): \Illuminate\Http\JsonResponse
    {
        $data = json_decode(ProclubsApiService::clubsInfo(Platforms::getPlatform($platform), $clubId));

        return response()->json($data);
    }

    public function members(int $clubId, string $platform): \Illuminate\Http\JsonResponse
    {
        $data = json_decode(ProclubsApiService::memberStats(Platforms::getPlatform($platform), $clubId));

        return response()->json($data);
    }

    public function career(int $clubId, string $platform): \Illuminate\Http\JsonResponse
    {
        $data = json_decode(ProclubsApiService::careerStats(Platforms::getPlatform($platform), $clubId));

        return response()->json($data);
    }

    public function season(int $clubId, string $platform): \Illuminate\Http\JsonResponse
    {
        $data = json_decode(ProclubsApiService::seasonStats(Platforms::getPlatform($platform), $clubId));

        return response()->json($data);
    }

    public function settings(string $clubName, string $platform): \Illuminate\Http\JsonResponse
    {
        $data = json_decode(ProclubsApiService::settings(Platforms::getPlatform($platform), $clubName));

        return response()->json($data);
    }

    public function search(string $clubName, string $platform): \Illuminate\Http\JsonResponse
    {
        $data = json_decode(ProclubsApiService::search(Platforms::getPlatform($platform), $clubName));

        return response()->json($data);
    }

    public function league(int $clubId, string $platform): \Illuminate\Http\JsonResponse
    {
        $data = json_decode(ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::LEAGUE));

        return response()->json($data);
    }

    public function leaderboard(string $platform, string $leaderboardType): \Illuminate\Http\JsonResponse
    {
        $data = json_decode(ProclubsApiService::leaderboard(Platforms::getPlatform($platform), $leaderboardType));

        return response()->json($data);
    }

    public function cup(int $clubId, string $platform): \Illuminate\Http\JsonResponse
    {
        $data = json_decode(ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::CUP));

        return response()->json($data);
    }

    public function player(int $clubId, string $platform, string $playerName): \Illuminate\Http\JsonResponse
    {
        $data = ProclubsApiService::playerStats(Platforms::getPlatform($platform), $clubId, $playerName);

        return response()->json($data);
    }

    public function squad(ResultService $resultService): \Illuminate\Http\JsonResponse
    {
        $data = $resultService->getCachedData($this->clubId, $this->platform, 'squad');

        return response()->json($data);
    }

    public function compare(int $clubId, string $platform, string $player1, string $player2, ResultService $resultService): \Illuminate\Http\JsonResponse
    {
        $data = $resultService->getPlayerComparisonData($clubId, $platform, $player1, $player2);

        return response()->json($data);
    }

    public function ranking(ResultService $resultService): \Illuminate\Http\JsonResponse
    {
        $data = [
            'rankings' => $resultService->getRankingData($this->clubId, $this->platform),
            'perMatchRankings' => $resultService->getCustomRankingData($this->clubId, $this->platform),
        ];

        return response()->json($data);
    }

    public function form(): \Illuminate\Http\JsonResponse
    {
        $data = [];

        return response()->json($data);
    }
}
