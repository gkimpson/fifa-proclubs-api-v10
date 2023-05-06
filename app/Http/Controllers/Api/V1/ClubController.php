<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ChartService;
use App\Services\ClubService;
use App\Services\ResultService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function __construct(Request $request, ClubService $clubService)
    {
        $this->clubService = $clubService;
        $this->clubService->getRouteParams($request);
    }

    public function index(): JsonResponse
    {
        return $this->clubService->index();
    }

    public function members(): JsonResponse
    {
        return $this->clubService->members();
    }

    public function career(): JsonResponse
    {
        return $this->clubService->career();
    }

    public function season(): JsonResponse
    {
        return $this->clubService->season();
    }

    public function settings(): JsonResponse
    {
        return $this->clubService->settings();
    }

    public function search(): JsonResponse
    {
        return $this->clubService->search();
    }

    public function league(): JsonResponse
    {
        return $this->clubService->league();
    }

    public function leaderboard(): JsonResponse
    {
        return $this->clubService->leaderboard();
    }

    public function cup(): JsonResponse
    {
        return $this->clubService->cup();
    }

    public function player(): JsonResponse
    {
        return $this->clubService->player();
    }

    public function squad(ResultService $resultService): JsonResponse
    {
        return $this->clubService->squad($resultService);
    }

    public function compare(ResultService $resultService, ChartService $chartService): View
    {
        return $this->clubService->compare($resultService, $chartService);
    }

    public function ranking(ResultService $resultService): JsonResponse
    {
        return $this->clubService->ranking($resultService);
    }

    public function form(): JsonResponse
    {
        return $this->clubService->form();
    }
}
