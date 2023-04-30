<?php

declare(strict_types=1);

namespace App\Enums;

enum MatchTypes
{
    case LEAGUE;
    case CUP;

    public static function all(): array
    {
        return collect(self::cases())->map(function ($matchType) {
            return $matchType->name();
        })->all();
    }

    public function name(): string
    {
        return match ($this) {
            self::LEAGUE => 'gameType9',
            self::CUP => 'gameType13',
        };
    }
}
