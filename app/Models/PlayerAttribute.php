<?php

namespace App\Models;

use App\Helpers\PlayerAttributesHelper;
use Assert\Assertion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PlayerAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
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

    public function scopeFilter(\Illuminate\Contracts\Database\Query\Builder $builder)
    {
        $playerAttributes = PlayerAttributesHelper::getPlayerAttributeNames();

        foreach ($playerAttributes as $attribute) {
            $builder->when(request($attribute), function ($builder) use ($attribute) {
                $builder->where($attribute, '>=', request($attribute));
            });
        }

        $builder->with('player');
    }

    // create a relationship with the player model
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
