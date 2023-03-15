<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $data = [
            'results' => Result::getAll()->paginate(10),
        ];

//        dump($data['results'][0]->properties['clubs'][0]['name']);
        dump($data['results'][0]);
        /**
         * @todo
         * 0 - Club name, Total Pts, Total Wins, Draws, Losses
         * 1 - Div ranking (Current Div, Season No, Pts, Games remaining & W-L-D form, pts to next division/title)
         * 2 - Members (random member on dashboard, total members, members by position, link to view all members)
         * 3 - Last Match (scoreline, link to all matches)
         * 4 - Trophies (if any)
         * 5 - Club history (Seasons played, total games, titles won, highest pts total, promotions, relegations)
         * 6 - Club Logo with W-L-D % in a circular graph around the emblem
         * 7 - Best season finish with animation like EA site
         * 8 - look into caching....
         */

        return view('dashboard.index', $data);
    }
}
