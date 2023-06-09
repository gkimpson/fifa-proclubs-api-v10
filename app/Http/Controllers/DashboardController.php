<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Result;

class DashboardController extends Controller
{
    public function index()
    {
        $data['results'] = Result::orderBy('match_date', 'desc')->get()->paginate(10);

        return view('dashboard.index', $data);
    }
}
