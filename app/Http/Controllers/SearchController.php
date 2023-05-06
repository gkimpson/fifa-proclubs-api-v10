<?php

namespace App\Http\Controllers;

use App\Enums\Platforms;
use App\Http\Requests\SearchPostRequest;
use App\Services\ClubService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public array $platforms = [];

    protected ClubService $clubService;

    public function __construct(Request $request)
    {
        $this->platforms = Platforms::generateDropdownValues();
        $this->clubService = new ClubService;
        $this->clubService->getRouteParams($request);
    }

    public function index()
    {
        $data = [
            'platforms' => $this->platforms,
        ];

        return view('search.index', $data);
    }

    public function submit(SearchPostRequest $request)
    {
        $request->validated();

        $data = [
            'platforms' => $this->platforms,
            'clubs' => json_decode($this->clubService->search()->getContent()),
        ];

        return view('search.index', $data);
    }
}
