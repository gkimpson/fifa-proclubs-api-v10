<?php

namespace App\Models;

use App\Helpers\PlayerAttributesHelper;
use Assert\Assert;
use Assert\Assertion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\PlayerAttribute
 *
 * @property int $id
 * @property int $player_id
 * @property string $favourite_position
 * @property int $acceleration
 * @property int $aggression
 * @property int $agility
 * @property int $attack_position
 * @property int $balance
 * @property int $ball_control
 * @property int $crossing
 * @property int $curve
 * @property int $dribbling
 * @property int $finishing
 * @property int $free_kick_accuracy
 * @property int $gk_diving
 * @property int $gk_handling
 * @property int $gk_kicking
 * @property int $gk_positioning
 * @property int $gk_reflexes
 * @property int $heading_accuracy
 * @property int $interceptions
 * @property int $jumping
 * @property int $long_pass
 * @property int $long_shots
 * @property int $marking
 * @property int $penalties
 * @property int $reactions
 * @property int $short_pass
 * @property int $shot_power
 * @property int $slide_tackle
 * @property int $sprint_speed
 * @property int $stamina
 * @property int $stand_tackle
 * @property int $strength
 * @property int $unsure_attribute
 * @property int $vision
 * @property int $volleys
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Player $player
 *
 * @method static \Database\Factories\PlayerAttributeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute filter()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereAcceleration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereAggression($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereAgility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereAttackPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereBallControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereCrossing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereCurve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereDribbling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereFavouritePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereFinishing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereFreeKickAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereGkDiving($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereGkHandling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereGkKicking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereGkPositioning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereGkReflexes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereHeadingAccuracy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereInterceptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereJumping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereLongPass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereLongShots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereMarking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute wherePenalties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereReactions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereShortPass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereShotPower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereSlideTackle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereSprintSpeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereStamina($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereStandTackle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereStrength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereUnsureAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereVision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerAttribute whereVolleys($value)
 *
 * @mixin \Eloquent
 */
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
