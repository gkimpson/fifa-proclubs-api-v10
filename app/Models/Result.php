<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Result
 *
 * @property int $id
 * @property string $match_id
 * @property int $home_team_id
 * @property int $away_team_id
 * @property int $home_team_goals
 * @property int $away_team_goals
 * @property int $home_team_player_count
 * @property int $away_team_player_count
 * @property string $outcome
 * @property string $match_date
 * @property string $platform
 * @property array|null $properties
 * @property string|null $media
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\ResultFactory factory($count = null, $state = [])
 * @method static Builder|Result newModelQuery()
 * @method static Builder|Result newQuery()
 * @method static Builder|Result query()
 * @method static Builder|Result whereAwayTeamGoals($value)
 * @method static Builder|Result whereAwayTeamId($value)
 * @method static Builder|Result whereAwayTeamPlayerCount($value)
 * @method static Builder|Result whereCreatedAt($value)
 * @method static Builder|Result whereHomeTeamGoals($value)
 * @method static Builder|Result whereHomeTeamId($value)
 * @method static Builder|Result whereHomeTeamPlayerCount($value)
 * @method static Builder|Result whereId($value)
 * @method static Builder|Result whereMatchDate($value)
 * @method static Builder|Result whereMatchId($value)
 * @method static Builder|Result whereMedia($value)
 * @method static Builder|Result whereOutcome($value)
 * @method static Builder|Result wherePlatform($value)
 * @method static Builder|Result whereProperties($value)
 * @method static Builder|Result whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id', 'home_team_id', 'away_team_id', 'home_team_goals', 'away_team_goals', 'home_team_player_count',
        'away_team_player_count', 'outcome', 'match_date', 'properties', 'platform', 'media',
    ];

    protected $casts = [
        'properties' => 'json',
    ];

    protected static function booted(): void
    {
        if (auth()->check() && auth()->user()->club_id) {
            self::getByClubId();
        }
    }

    private static function getByClubId(): void
    {
        // we don't want this happening via the command line scripts
        static::addGlobalScope('home_team', function (Builder $builder) {
            $builder->where('home_team_id', auth()->user()->club_id)
                ->orWhere('away_team_id', auth()->user()->club_id);
        });
    }

    public function scopeByTeam(Builder $query, int $teamId): Builder
    {
        return $query->where('home_team_id', $teamId)
            ->orWhere('away_team_id', $teamId)
            ->latest()
            ->first();
    }

    public function scopeHomeTeam(Builder $query, int $teamId): Builder
    {
        return $query->where('home_team_id', $teamId);
    }

    public function scopeAwayTeam(Builder $query, int $teamId): Builder
    {
        return $query->where('away_team_id', $teamId);
    }
}
