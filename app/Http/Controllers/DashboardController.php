<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResultResource;
use App\Models\Result;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index()
    {
        $results = Result::get()->paginate(10);
        return new ResultResource($results);
    }
}
