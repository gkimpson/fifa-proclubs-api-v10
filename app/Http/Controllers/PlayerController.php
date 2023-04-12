<?php

namespace App\Http\Controllers;

use App\Helpers\PlayerAttributesHelper;
use App\Models\PlayerAttribute;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function search(Request $request)
    {
        $data = [
            'attributes' => PlayerAttributesHelper::getPlayerAttributeNames(),
            'players' => PlayerAttribute::filter()->paginate(10),
        ];

        return view('player.search', $data);
    }
}
