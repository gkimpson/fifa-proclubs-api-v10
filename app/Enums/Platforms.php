<?php

namespace App\Enums;

use Exception;
use Illuminate\Support\Str;

enum Platforms
{
    case PC;
    case PS4;
    case PS5;
    case XBOX_ONE;
    case XBOX_SERIES_SX;

    public static function all(): array
    {
        return collect(self::cases())->map(function ($platform) {
            return $platform->name();
        })->all();
    }

    public static function generateDropdownValues(): array
    {
        return collect(self::cases())->mapWithKeys(function ($platform) {
            $key = $platform->name();
            $value = Str::replace('_', ' ', $platform->name);

            return [$key => $value];
        })->all();
    }

    // todo: add validation for in: with request validation
    public static function generateValidationIn()
    {
    }

    public static function getPlatform(string $platform)
    {
        return match ($platform) {
            'pc' => self::PC,
            'ps4' => self::PS4,
            'ps5' => self::PS5,
            'xboxone' => self::XBOX_ONE,
            'xbox-series-xs' => self::XBOX_SERIES_SX,
            default => throw new Exception('Unexpected platform value')
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::PC => 'pc',
            self::PS4 => 'ps4',
            self::PS5 => 'ps5',
            self::XBOX_ONE => 'xboxone',
            self::XBOX_SERIES_SX => 'xbox-series-xs',
        };
    }
}
