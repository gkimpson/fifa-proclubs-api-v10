<?php

namespace App\Http\Controllers;

use App\Helpers\PlayerAttributesHelper;
use App\Models\PlayerAttribute;
class PlayerController extends Controller
{
    public function search()
    {
        $data = [
            'attributes' => PlayerAttributesHelper::getPlayerAttributeNames(),
            'players' => PlayerAttribute::with('player')->filter()->paginate(10)->sortBy('player.player_name'),
        ];

        return view('player.search', $data);
    }
}
