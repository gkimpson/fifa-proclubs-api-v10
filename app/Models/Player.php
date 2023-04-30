<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Player
 *
 * @property int $id
 * @property int $club_id
 * @property int|null $ea_player_id
 * @property string $platform
 * @property string $player_name
 * @property string $attributes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PlayerAttribute|null $playerAttributes
 *
 * @method static \Database\Factories\PlayerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Player findByClubAndPlatform(int $clubId, string $platform)
 * @method static \Illuminate\Database\Eloquent\Builder|Player findByClubAndPlatformAndPlayerName(int $clubId, string $platform, string $playerName)
 * @method static \Illuminate\Database\Eloquent\Builder|Player newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player query()
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereEaPlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player wherePlayerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Player extends Model
{
    use HasFactory;

    protected $fillable = ['club_id', 'ea_player_id', 'platform', 'player_name', 'attributes'];

    /**
     * Scope a query to only include the player with the specified club ID, platform, and player name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindByClubAndPlatformAndPlayerName(object $query, int $clubId, string $platform, string $playerName): Player|null
    {
        return $query->where('club_id', $clubId)
            ->where('platform', $platform)
            ->where('player_name', $playerName)
            ->orderBy('player_name')
            ->first();
    }

    public function scopeFindByClubAndPlatform(object $query, int $clubId, string $platform): \Illuminate\Database\Eloquent\Collection
    {
        return $query->where('club_id', $clubId)
            ->where('platform', $platform)
            ->orderBy('player_name')
            ->get();
    }

    public function playerAttributes(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PlayerAttribute::class);
    }
}
