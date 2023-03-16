<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = ['match_id', 'home_team_id', 'away_team_id', 'home_team_goals', 'away_team_goals', 'home_team_player_count', 'away_team_player_count', 'outcome', 'match_date', 'properties', 'platform', 'media'];

    protected $casts = [
        'properties' => 'json',
    ];

    protected static function booted()
    {
        if (\Auth::check() && auth()->user()->club_id) {
            self::getByClubId();
        }
    }

    private static function getByClubId()
    {
        // we don't want this happening via the command line scripts
        static::addGlobalScope('home_team', function (Builder $builder) {
            $builder->where('home_team_id', auth()->user()->club_id)
                ->orWhere('away_team_id', auth()->user()->club_id);
        });
    }

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
        } catch (\Exception $e) {

        }

        return $results;
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
        $data = collect($players)->map(function ($clubPlayers) {
            return collect($clubPlayers)->map(function ($player) {
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
                    'vproattr' => self::getProAttributes($player->vproattr),
                    'vprohackreason' => $player->vprohackreason,
                    'wins' => $player->wins,
                    'playername' => $player->playername,
                    'properties' => $player,
                ];
            })->toArray();
        })->toArray();

        return $data;
    }

    // TODO: generate FUT style card with attributes
    private static function getProAttributes(string $attributes): string
    {
        return $attributes;
    }

    private static function getMatchOutcome(array $clubData): string
    {
        return ($clubData['wins'] == 1) ? 'homewin' : (($clubData['losses'] == 1) ? 'awaywin' : (($clubData['ties'] == 1) ? 'draw' : ''));
    }

    public static function generateInsertData(array $result, string $platform): array
    {
        $carbonDate = Carbon::now();
        $carbonDate->timestamp($result['timestamp']);
        $clubs = array_values($result['clubs']);

        $data = [
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

        return $data;
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
                } catch (\Exception $e) {
                    dump($e->getMessage());
                }
            }
        }

        return $inserted;
    }
}
