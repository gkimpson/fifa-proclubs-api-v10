<?php

namespace App\Enums;

enum MatchTypes
{
    case LEAGUE;
    case CUP;

    public function name(): string
    {
        return match ($this) {
            self::LEAGUE => 'gameType9',
            self::CUP => 'gameType13',
        };
    }

    public static function all(): array
    {
        return collect(self::cases())->map(function ($matchType) {
            return $matchType->name();
        })->all();
    }
}
