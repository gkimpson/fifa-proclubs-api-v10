<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Result;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): mixed
    {
        return Result::all();
    }
}
