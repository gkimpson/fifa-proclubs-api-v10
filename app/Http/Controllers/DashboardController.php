<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $data = [
            'results' => Result::getAll()->paginate(20),
        ];

        return view('dashboard.index', $data);
    }
}
