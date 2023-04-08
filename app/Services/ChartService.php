<?php

namespace App\Services;

use App\Models\Player;
use Assert\Assertion;
use Illuminate\Support\Str;

class ChartService
{
    public static function getPlayerComparisonData(int $clubId, string $platform, string $player1, string $player2): array
    {
        $player1Attributes = self::getFormattedPlayerAttributes($clubId, $platform, $player1);
        $player2Attributes = self::getFormattedPlayerAttributes($clubId, $platform, $player2);

        return [
            'player1' => $player1Attributes,
            'player2' => $player2Attributes,
        ];
    }

    public static function getFormattedPlayerAttributes(int $clubId, string $platform, string $player): array
    {
        $attributes = self::getPlayer($clubId, $platform, $player);
        Assertion::notEmpty($attributes, 'Attributes cannot be empty');
        $attributesCollection = Str::of($attributes)->explode('|')->filter()->map(function ($value) {
            return (int) $value;
        });

        $attribute_names = self::getAttributeNames();
        $attribute_groups = self::getAttributeGroups();

        return [
            'name' => $player,
            'averages' => self::getCategoryAverages($attribute_groups, $attributesCollection),
            'mapped' => self::getMappedAttributes($attribute_names, $attributesCollection),
        ];
    }

    private static function getPlayer(int $clubId, string $platform, string $player)
    {
        $player = Player::findByClubAndPlatformAndPlayerName($clubId, $platform, $player);

        return ($player && isset($player->attributes)) ? $player->attributes : null;
    }

    private static function getAttributeNames()
    {
        return [
            0 => 'ACCELERATION',
            1 => 'SPRINT SPEED',
            2 => 'AGILITY',
            3 => 'BALANCE',
            4 => 'JUMPING',
            5 => 'STAMINA',
            6 => 'STRENGTH',
            7 => 'REACTIONS',
            8 => 'AGGRESSION',
            9 => 'UNSURE WTF',
            10 => 'INTERCEPTIONS',
            11 => 'ATTACK POSITION',
            12 => 'VISION',
            13 => 'BALL CONTROL',
            14 => 'CROSSING',
            15 => 'DRIBBLING',
            16 => 'FINISHING',
            17 => 'FREE KICK ACCURACY',
            18 => 'HEADING ACCURACY',
            19 => 'LONG PASS',
            20 => 'SHORT PASS',
            21 => 'MARKING',
            22 => 'SHOT POWER',
            23 => 'LONG SHOTS',
            24 => 'STAND TACKLE',
            25 => 'SLIDE TACKLE',
            26 => 'VOLLEYS',
            27 => 'CURVE',
            28 => 'PENALTIES',
            29 => 'GK Diving',
            30 => 'GK Handling',
            31 => 'GK Kicking',
            32 => 'GK Reflexes',
            33 => 'GK Positioning',
        ];
    }

    private static function getAttributeGroups()
    {
        return [
            'shooting' => [16, 17, 18, 22, 23, 26, 28], // finishing, free-kick accuracy, heading accuracy, shot power, long shots, volleys, penalties
            'passing' => [12, 14, 19, 20, 27],  // vision, crossing, long pass, short pass, curve
            'dribbling' => [2, 3, 11, 13, 15], // agility, balance, attack position, ball control, dribbling
            'defending' => [10, 21, 24, 25], // interceptions, marking, stand tackle, slide tackle
            'physical' => [4, 5, 7, 8], // jumping, stamina, strength, reactions, aggression
            'pace' => [0, 1], // acceleration, speed
            'goalkeeping' => [29, 30, 31, 32, 33], // GK only - diving, handling, kicking, reflexes, positioning
        ];
    }

    private static function getCategoryAverages($attribute_groups, $attributes): array
    {
        return collect($attribute_groups)->map(function ($attribute_group) use ($attributes) {
            return round(self::attribute_values_averages($attributes, $attribute_group), 0);
        })->all();
    }

    private static function getMappedAttributes($attribute_names, $attributes): array
    {
        return collect($attribute_names)
            ->map(function ($attribute_name, $attribute_key) use ($attributes) {
                $slug = Str::slug($attribute_name, '-');

                return [$slug => $attributes[$attribute_key]];
            })
            ->collapse()
            ->all();
    }

    private static function attribute_values_averages(object $attributes, array $attribute_group)
    {
        $collection = collect($attribute_group)->map(function ($key) use ($attributes) {
            return $attributes[$key];
        })->reject(function ($key) {
            return empty($key);
        });

        return $collection->average();
    }

    public function index()
    {
    }
}