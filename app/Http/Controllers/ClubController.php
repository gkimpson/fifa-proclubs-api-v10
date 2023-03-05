<?php

namespace App\Http\Controllers;

use App\Enums\Platforms;
use App\Services\ProClubsApiService;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($clubId, $platform)
    {
        return ProClubsApiService::clubsInfo(Platforms::getPlatform($platform), $clubId);
    }

    public function members($clubId, $platform)
    {
        return ProClubsApiService::memberStats(Platforms::getPlatform($platform), $clubId);
    }

    public function career($clubId, $platform)
    {
        return ProClubsApiService::careerStats(Platforms::getPlatform($platform), $clubId);
    }

    public function season($clubId, $platform)
    {
        return ProClubsApiService::seasonStats(Platforms::getPlatform($platform), $clubId);
    }

    public function settings($clubName, $platform)
    {
        return ProClubsApiService::settings(Platforms::getPlatform($platform), $clubName);
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
