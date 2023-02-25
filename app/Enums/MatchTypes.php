<?php

namespace App\Enums;

enum MatchTypes
{
    case LEAGUE;
    case CUP;

    public function name(): string
    {
        return match($this)
        {
            self::LEAGUE => 'gameType9',
            self::CUP => 'gameType13',
        };
    }
}
