<?php

namespace App\Models;

use App\Enums\Outcomes;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

    protected $fillable = ['match_id', 'home_team_id', 'away_team_id', 'home_team_goals', 'away_team_goals', 'home_team_player_count', 'away_team_player_count', 'outcome', 'match_date', 'properties', 'platform', 'media'];

    protected $casts = [
        'properties' => 'json',
    ];

    public static function getAll()
    {
        return Result::all();
    }

    public static function formatJsonData(string $data): array
    {
        $results = [];

        try {
            $collection = collect(json_decode($data));

            $results = $collection->map(function ($value) {
                return [
                    'matchId' => $value->matchId,
                    'timestamp' => $value->timestamp,
                    'clubs' => self::getClubsData($value->clubs),
                    'players' => self::getPlayerData($value->players),
                    'aggregate' => $value->aggregate,
                ];
            })->all();
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return $results;
    }

    public static function generateInsertData(array $result, string $platform): array
    {
        $carbonDate = Carbon::now();
        $carbonDate->timestamp($result['timestamp']);
        $clubs = array_values($result['clubs']);

        return [
            'match_id' => $result['matchId'],
            'home_team_id' => $clubs[0]['id'],
            'away_team_id' => $clubs[1]['id'],
            'home_team_goals' => $clubs[0]['goals'],
            'away_team_goals' => $clubs[1]['goals'],
            'home_team_player_count' => count($result['players'][$clubs[0]['id']]),
            'away_team_player_count' => count($result['players'][$clubs[1]['id']]),
            'outcome' => self::getMatchOutcome($clubs[0]),
            'match_date' => $carbonDate->format('Y-m-d H:i:s'),
            'properties' => [
                'clubs' => $result['clubs'],
                'players' => $result['players'],
                'aggregate' => $result['aggregate'], // aggregate is used for consistency as EA use the same naming convention - this is basically 'team stats' for that match
            ],
            'platform' => $platform,
        ];
    }

    public static function insertMatches(array $results, string $platform): int
    {
        $inserted = 0;

        foreach ($results as $result) {
            if (Result::where('match_id', '=', $result['matchId'])->doesntExist()) {
                $data = self::generateInsertData($result, $platform);

                try {
                    Result::create($data);
                    $inserted++;
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }

        return $inserted;
    }

    protected static function booted()
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

    private static function getClubsData(object $clubs): array
    {
        return collect($clubs)->map(function ($club, $clubId) {
            return [
                'id' => $clubId,
                'name' => $club->details->name ?? 'TEAM DISBANDED',
                'goals' => $club->goals,
                'goalsAgainst' => $club->goalsAgainst,
                'seasonId' => $club->seasonId ?? null,
                'winnerByDnf' => $club->winnerByDnf,
                'wins' => $club->wins,
                'losses' => $club->losses,
                'ties' => $club->ties,
                'gameNumber' => $club->gameNumber,
                'result' => $club->result,
                'teamId' => $club->details->teamId ?? null,
            ];
        })->values()->toArray();
    }

    private static function getPlayerData(object $players): array
    {
        return collect($players)->map(function ($clubPlayers) {
            return collect($clubPlayers)->map(function ($player) {
                return self::getPlayerStats($player);
            })->toArray();
        })->toArray();
    }

    private static function getPlayerStats($player): array
    {
        return [
            'assists' => $player->assists,
            'cleansheetsany' => $player->cleansheetsany,
            'cleansheetsdef' => $player->cleansheetsdef,
            'cleansheetsgk' => $player->cleansheetsgk,
            'goals' => $player->goals,
            'goalsconceded' => $player->goalsconceded,
            'losses' => $player->losses,
            'mom' => $player->mom,
            'passattempts' => $player->passattempts,
            'passesmade' => $player->passesmade,
            'pos' => $player->pos,
            'realtimegame' => $player->realtimegame,
            'realtimeidle' => $player->realtimeidle,
            'redcards' => $player->redcards,
            'saves' => $player->saves,
            'SCORE' => $player->SCORE,
            'shots' => $player->shots,
            'tackleattempts' => $player->tackleattempts,
            'tacklesmade' => $player->tacklesmade,
            'vproattr' => $player->vproattr,
            'vprohackreason' => $player->vprohackreason,
            'wins' => $player->wins,
            'playername' => $player->playername,
            'properties' => $player,
        ];
    }

    private static function getMatchOutcome(array $clubData): string
    {
        switch (true) {
            case $clubData['wins'] === '1':
                return Outcomes::HOMEWIN->name();
            case $clubData['losses'] === '1':
                return Outcomes::AWAYWIN->name();
            case $clubData['ties'] === '1':
                return Outcomes::DRAW->name();
            default:
                throw new Exception('Invalid club data provided.');
        }
    }

    public function scopeByTeam($query, $teamId)
    {
        return $query->where('home_team_id', $teamId)
            ->orWhere('away_team_id', $teamId)
            ->latest()
            ->first();
    }
}
