<?php

namespace App\Models;

use App\Helpers\PlayerAttributesHelper;
use Assert\Assert;
use Assert\Assertion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PlayerAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'favourite_position',
        'acceleration',
        'aggression',
        'agility',
        'attack_position',
        'balance',
        'ball_control',
        'crossing',
        'curve',
        'dribbling',
        'finishing',
        'free_kick_accuracy',
        'gk_diving',
        'gk_handling',
        'gk_kicking',
        'gk_positioning',
        'gk_reflexes',
        'heading_accuracy',
        'interceptions',
        'jumping',
        'long_pass',
        'long_shots',
        'marking',
        'penalties',
        'reactions',
        'short_pass',
        'shot_power',
        'slide_tackle',
        'sprint_speed',
        'stamina',
        'stand_tackle',
        'strength',
        'unsure_attribute',
        'vision',
        'volleys',
    ];

    /**
     * returns the first character of the position e.g 'G' Goalkeeper, 'D' Defender, 'M' Midfielder, 'F' Forward
     *
     * @return string
     */
    public static function generateFavouritePosition(string $position): ?string
    {
        if (empty($position)) {
            return null;
        }

        $firstCharacter = ucfirst($position[0]);
        Assertion::inArray($firstCharacter, ['G', 'D', 'M', 'F'], 'Invalid position');

        return $firstCharacter;
    }

    public static function generateAttributes($attributes): array
    {
        Assertion::notEmpty($attributes, 'Attributes cannot be empty');

        $attributesCollection = self::parseAttributes($attributes);
        $attributeNames = PlayerAttributesHelper::getPlayerAttributeNames();

        return self::getMappedAttributes($attributeNames, $attributesCollection);
    }

    public static function parseAttributes(string $attributes)
    {
        $attributes = Str::of($attributes)->explode('|')->filter()->map(function ($attribute) {
            return (int) $attribute;
        });

        return $attributes;
    }

    private static function getMappedAttributes(array $attributeNames, $attributes): array
    {
        Assertion::eq(count($attributeNames), count($attributes), 'Attribute names and attributes must be the same length');
        return collect($attributeNames)
            ->map(function ($attributeName, $attributeKey) use ($attributes) {
                $slug = Str::slug($attributeName, '_');

                return [$slug => $attributes[$attributeKey]];
            })
            ->collapse()
            ->all();
    }

    public function scopeFilter(\Illuminate\Contracts\Database\Query\Builder $builder)
    {
        $playerAttributes = PlayerAttributesHelper::getPlayerAttributeNames();

        $filteredAttributes = collect($playerAttributes)->filter(function ($attribute) {
            return request($attribute) !== '';
        });

        foreach ($filteredAttributes as $attribute) {
            $builder->when(request($attribute), function ($builder) use ($attribute) {
                $builder->where($attribute, '>=', request($attribute));
                Assert::that(request($attribute))->integerish('Attribute must be an integerish value');
            });
        }

        $builder->with('player');
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
