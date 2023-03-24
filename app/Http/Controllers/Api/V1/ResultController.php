<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResultResource;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::get()->paginate(10);
        return ResultResource::collection($results);
    }

    public function show(Result $result)
    {
        return ResultResource::make($result);
    }
}
