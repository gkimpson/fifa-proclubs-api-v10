<?php

namespace App\Helpers;

class PlayerAttributesHelper
{
    public static function getPlayerAttributeNames()
    {
        return config('proclubs.player_attributes.attributes');
    }

    public static function getAttributeTailwindCssClass(string $rating): string
    {
        $attributeColour = [
            'very_poor' => 'bg-red-500',
            'poor' => 'bg-red-500',
            'fair' => 'bg-yellow-500',
            'good' => 'bg-green-500',
            'very_good' => 'bg-green-500',
            'excellent' => 'bg-green-500',
        ];

        $ratings = [
            '0-39' => 'very_poor',
            '40-49' => 'poor',
            '50-69' => 'fair',
            '70-79' => 'good',
            '80-89' => 'very_good',
            '90-99' => 'excellent',
        ];

        foreach ($ratings as $range => $category) {
            [$min, $max] = explode('-', $range);
            if ($rating >= $min && $rating <= $max) {
                break;
            }
        }

        return $attributeColour[$category];
    }
}
