<?php

namespace App\Models;

use Assert\Assertion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PlayerAttribute extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function generateAttributes($attributes): array
    {
        Assertion::notEmpty($attributes, 'Attributes cannot be empty');

        $attributesCollection = self::parseAttributes($attributes);
        $attributeNames = self::getAttributeNames();

        return self::getMappedAttributes($attributeNames, $attributesCollection);
    }
    public static function parseAttributes(string $attributes)
    {
        $attributes = Str::of($attributes)->explode('|')->filter()->map(function ($attribute) {
            return (int) $attribute;
        });

        return $attributes;
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
            30 => 'GK HANDLING',
            31 => 'GK KICKING',
            32 => 'GK REFLEXES',
            33 => 'GK POSITIONING',
        ];
    }
}
