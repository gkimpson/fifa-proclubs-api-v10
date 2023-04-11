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

    public static function getClubComparisonData(int $clubId, string $platform)
    {
        $clubPlayers = Player::findByClubAndPlatform($clubId, $platform);
        $clubPlayers = $clubPlayers->map(function ($player) {
            return self::getFormattedPlayerAttributes($player->club_id, $player->platform, $player->player_name, true);
        });

        return [
            'players' => $clubPlayers->toArray(),
        ];
    }

    public static function getFormattedPlayerAttributes(int $clubId, string $platform, string $player, $valuesOnly = false): array
    {
        $attributes = self::getPlayer($clubId, $platform, $player);
        Assertion::notEmpty($attributes, 'Attributes cannot be empty');
        $attributesCollection = Str::of($attributes)->explode('|')->filter()->map(function ($value) {
            return (int) $value;
        });

        $attribute_names = self::getAttributeNames();
        $attribute_groups = self::getAttributeGroups();

        // TODO - refactor this to not duplicate the category averages (will break the other chart if averages key removed)
        return [
            'name' => $player,
            'averages' => self::getCategoryAverages($attribute_groups, $attributesCollection, $valuesOnly),
            'mapped' => self::getMappedAttributes($attribute_names, $attributesCollection),
            'data' => self::getCategoryAverages($attribute_groups, $attributesCollection, $valuesOnly),
        ];
    }

    private static function getPlayer(int $clubId, string $platform, string $player)
    {
        $player = Player::findByClubAndPlatformAndPlayerName($clubId, $platform, $player);

        return ($player && isset($player->attributes)) ? $player->attributes : null;
    }

    private static function getAttributeNames()
    {
        // TODO - put this in a config file
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
            9 => 'UNSURE ATTRIBUTE',
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
            29 => 'GK DIVING',
            30 => 'GK HANDLINE',
            31 => 'GK KICKING',
            32 => 'GK REFLEXES',
            33 => 'GK POSITIONING',
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

    private static function getCategoryAverages($attributeGroups, $attributes, $valuesOnly = false): array
    {
        $data = collect($attributeGroups)->map(function ($attributeGroup) use ($attributes) {
            return round(self::attribute_values_averages($attributes, $attributeGroup), 0);
        });

        return $valuesOnly ? $data->values()->all() : $data->all();
    }

    private static function getMappedAttributes($attributeNames, $attributes): array
    {
        return collect($attributeNames)
            ->map(function ($attributeName, $attributeKey) use ($attributes) {
                $slug = Str::slug($attributeName, '_');

                return [$slug => $attributes[$attributeKey]];
            })
            ->collapse()
            ->all();
    }

    private static function attribute_values_averages(object $attributes, array $attributeGroup)
    {
        $collection = collect($attributeGroup)->map(function ($key) use ($attributes) {
            return $attributes[$key];
        })->reject(function ($key) {
            return empty($key);
        });

        return $collection->average();
    }
}
