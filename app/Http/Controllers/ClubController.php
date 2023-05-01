<?php

namespace App\Http\Controllers;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use App\Services\ChartService;
use App\Services\ProclubsApiService;
use App\Services\ResultService;
use Exception;
use Illuminate\Contracts\View\View;
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
        $this->getRouteParams($request);
    }



    /**
     * @throws Exception
     */
    public function index(int $clubId, string $platform): JsonResponse
    {
        $data = json_decode(ProclubsApiService::clubsInfo(Platforms::getPlatform($platform), $clubId));

        return response()->json($data);
    }

    /**
     * @throws Exception
     */
    public function members(int $clubId, string $platform): JsonResponse
    {
        $data = json_decode(ProclubsApiService::memberStats(Platforms::getPlatform($platform), $clubId));

        return response()->json($data);
    }

    /**
     * @throws Exception
     */
    public function career(int $clubId, string $platform): JsonResponse
    {
        $data = json_decode(ProclubsApiService::careerStats(Platforms::getPlatform($platform), $clubId));

        return response()->json($data);
    }

    /**
     * @throws Exception
     */
    public function season(int $clubId, string $platform): JsonResponse
    {
        $data = json_decode(ProclubsApiService::seasonStats(Platforms::getPlatform($platform), $clubId));

        return response()->json($data);
    }

    /**
     * @throws Exception
     */
    public function settings(string $clubName, string $platform): JsonResponse
    {
        $data = json_decode(ProclubsApiService::settings(Platforms::getPlatform($platform), $clubName));

        return response()->json($data);
    }

    /**
     * @throws Exception
     */
    public function search(string $clubName, string $platform): JsonResponse
    {
        $data = json_decode(ProclubsApiService::search(Platforms::getPlatform($platform), $clubName));

        return response()->json($data);
    }

    /**
     * @throws Exception
     */
    public function league(int $clubId, string $platform): JsonResponse
    {
        $data = json_decode(ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::LEAGUE));

        return response()->json($data);
    }

    /**
     * @throws Exception
     */
    public function leaderboard(string $platform, string $leaderboardType): JsonResponse
    {
        $data = json_decode(ProclubsApiService::leaderboard(Platforms::getPlatform($platform), $leaderboardType));

        return response()->json($data);
    }

    /**
     * @throws Exception
     */
    public function cup(int $clubId, string $platform): JsonResponse
    {
        $data = json_decode(ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::CUP));

        return response()->json($data);
    }

    /**
     * @throws Exception
     */
    public function player(int $clubId, string $platform, string $playerName): JsonResponse
    {
        $data = ProclubsApiService::playerStats(Platforms::getPlatform($platform), $clubId, $playerName);

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

    public function getRouteParams(Request $request): void
    {
        $this->clubId = $request->route('clubId') ?? 0;
        $this->clubIds = $request->route('clubIds') ?? 0;
        $this->platform = $request->route('platform') ?? '';
        $this->player1 = $request->route('player1') ?? '';
        $this->player2 = $request->route('player2') ?? '';
    }
}
