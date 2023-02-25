<?php

namespace App\Enums;

enum Platforms
{
    case PS5;
    case PS4;
    case XBOXONE;
    case XBOXSERIESSX;
    case PC;

    public function name(): string
    {
        return match($this)
        {
            self::PS5 => 'ps5',
            self::PS4 => 'ps4',
            self::XBOXONE => 'xboxone',
            self::XBOXSERIESSX => 'xbox-series-xs',
            self::PC => 'pc',
        };
    }

    public static function all(): array
    {
        return collect(self::cases())->map(function($platform){
            return $platform->name();
        })->all();
    }
}
