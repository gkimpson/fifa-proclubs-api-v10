<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = ['club_id', 'ea_player_id', 'platform', 'player_name', 'attributes'];

    /**
     * Scope a query to only include the player with the specified club ID, platform, and player name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $clubId
     * @param  string  $platform
     * @param  string  $playerName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindByClubAndPlatformAndPlayerName(object $query, int $clubId, string $platform, string $playerName): \App\Models\Player|null
    {
        return $query->where('club_id', $clubId)
            ->where('platform', $platform)
            ->where('player_name', $playerName)
            ->first();
    }
}
