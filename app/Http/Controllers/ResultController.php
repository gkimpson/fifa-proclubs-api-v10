<?php

namespace App\Http\Controllers;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use App\Http\Requests\StoreResultRequest;
use App\Http\Requests\UpdateResultRequest;
use App\Models\Result;
use App\Services\ProClubsApiService;
use Illuminate\Http\Response;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $clubId = 310718;

        $user = auth()->user()->toArray();dump($user);

        $result = Result::first();dump($result);
        dd(ProClubsApiService::matchStats(Platforms::getPlatform($user['platform']), $clubId, MatchTypes::LEAGUE));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResultRequest $request): Response
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Result $result): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResultRequest $request, Result $result): Response
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result): Response
    {
        //
    }
}
