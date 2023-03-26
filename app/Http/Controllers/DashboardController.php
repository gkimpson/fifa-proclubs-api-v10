<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResultResource;
use App\Models\Result;

class DashboardController extends Controller
{
    public function index()
    {
        $data['results'] = Result::get()->paginate(10);

        return view('dashboard.index', $data);
    }
}
