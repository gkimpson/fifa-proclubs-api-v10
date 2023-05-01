<?php

namespace App\Services;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClubService
{
    public int $clubId;
    public int $clubIds;
    public string $platform;
    public string $player1;
    public string $player2;
    public string $leaderboardType;
    public string $player;
    private $clubName;

    public function getRouteParams(Request $request): void
    {
        $this->clubId = $request->route('clubId') ?? 0;
        $this->clubIds = $request->route('clubIds') ?? 0;
        $this->platform = $request->route('platform') ?? '';
        $this->player1 = $request->route('player1') ?? '';
        $this->player2 = $request->route('player2') ?? '';
        $this->leaderboardType = $request->route('leaderboardType') ?? '';
        $this->player = $request->route('player') ?? '';
        $this->clubName = $request->route('clubName') ?? '';
    }

    public function index(): JsonResponse
    {
        $data = json_decode(ProclubsApiService::clubsInfo(Platforms::getPlatform($this->platform), $this->clubId));

        return response()->json($data);
    }

    public function members(): JsonResponse
    {
        $data = json_decode(ProclubsApiService::memberStats(Platforms::getPlatform($this->platform), $this->clubId));

        return response()->json($data);
    }

    public function career(): JsonResponse
    {
        $data = json_decode(ProclubsApiService::careerStats(Platforms::getPlatform($this->platform), $this->clubId));

        return response()->json($data);
    }

    public function season(): JsonResponse
    {
        $data = json_decode(ProclubsApiService::seasonStats(Platforms::getPlatform($this->platform), $this->clubId));

        return response()->json($data);
    }

    public function settings(): JsonResponse
    {
        $data = json_decode(ProclubsApiService::settings(Platforms::getPlatform($this->platform), $this->clubName));

        return response()->json($data);
    }

    public function search(): JsonResponse
    {
        $data = json_decode(ProclubsApiService::search(Platforms::getPlatform($this->platform), $this->clubName));

        return response()->json($data);
    }

    public function league(): JsonResponse
    {
        $data = json_decode(ProclubsApiService::matchStats(Platforms::getPlatform($this->platform), $this->clubId, MatchTypes::LEAGUE));

        return response()->json($data);
    }

    public function leaderboard(): JsonResponse
    {
        $data = json_decode(ProclubsApiService::leaderboard(Platforms::getPlatform($this->platform), $this->leaderboardType));

        return response()->json($data);
    }

    public function cup(): JsonResponse
    {
        $data = json_decode(ProclubsApiService::matchStats(Platforms::getPlatform($this->platform), $this->clubId, MatchTypes::CUP));

        return response()->json($data);
    }

    public function player(): JsonResponse
    {
        $data = ProclubsApiService::playerStats(Platforms::getPlatform($this->platform), $this->clubId, $this->player);

        return response()->json($data);
    }

    public function squad(ResultService $resultService): JsonResponse
    {
        $data = $resultService->getCachedData($this->clubId, $this->platform, 'squad');

        return response()->json($data);
    }

    public function compare(ResultService $resultService, ChartService $chartService): View
    {
        $data = [
            'playerData' => $resultService->getPlayerComparisonData($this->clubId, $this->platform, $this->player1, $this->player2),
            'chartData' => $chartService->getPlayerComparisonData($this->clubId, $this->platform, $this->player1, $this->player2),
        ];

        return view('club.compare', compact('data'));
    }

    public function compareAll(ChartService $chartService): View
    {
        $data = [
            'chartData' => $chartService->getClubComparisonData($this->clubId, $this->platform),
        ];

        return view('club.compareall', $data);
    }

    public function ranking(ResultService $resultService): JsonResponse
    {
        $data = [
            'rankings' => $resultService->getRankingData($this->clubId, $this->platform),
            'perMatchRankings' => $resultService->getCustomRankingData($this->clubId, $this->platform),
        ];

        return response()->json($data);
    }

    public function form(): JsonResponse
    {
        $data = [];

        return response()->json($data);
    }
}
