<?php

namespace App\Enums;

enum Outcomes
{
    case HOMEWIN;
    case AWAYWIN;
    case DRAW;

    public function name(): string
    {
        return match($this)
        {
            self::HOMEWIN => 'homewin',
            self::AWAYWIN => 'awaywin',
            self::DRAW => 'draw',
        };
    }

    public static function all(): array
    {
        return collect(self::cases())->map(function($outcomes){
            return $outcomes->name();
        })->all();
    }
}
