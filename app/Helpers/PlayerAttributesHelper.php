<?php

declare(strict_types=1);

namespace App\Helpers;

class PlayerAttributesHelper
{
    public static function getPlayerAttributeNames()
    {
        return config('proclubs.player_attributes.attributes');
    }

    public static function getAttributeTailwindCssClass(string $rating): string
    {
        $attributeColour = config('proclubs.player_attributes.attribute_tailwind_css_classes');
        $ratings = config('proclubs.player_attributes.attribute_ratings');

        foreach ($ratings as $range => $category) {
            [$min, $max] = explode('-', $range);
            if ($rating >= $min && $rating <= $max) {
                break;
            }
        }

        return $attributeColour[$category];
    }
}
