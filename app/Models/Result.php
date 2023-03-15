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

            foreach ($collection as $value) {
                $results[] = [
                    'matchId' => $value->matchId,
                    'timestamp' => $value->timestamp,
                    'clubs' => self::getClubsData($value->clubs),
                    'players' => self::getPlayerData($value->players),
                    'aggregate' => $value->aggregate,
                ];
            }

            return $results;
        } catch (\Exception $e) {
            // do some logging...
            // dd($e->getMessage());
            return $results;
        }
    }

    private static function getClubsData(object $clubs): array
    {
        $clubs = collect($clubs);
        $data = [];

        foreach ($clubs as $clubId => $club) {
            $data[] = [
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
        }

        return $data;
    }

    private static function getPlayerData(object $players): array
    {
        $players = collect($players);
        $data = [];

        foreach ($players as $clubId => $clubPlayer) {
            // loop through each player(s) for each club
            foreach ($players[$clubId] as $clubPlayer) {
                $data[$clubId][] = [
                    'assists' => $clubPlayer->assists,
                    'cleansheetsany' => $clubPlayer->cleansheetsany,
                    'cleansheetsdef' => $clubPlayer->cleansheetsdef,
                    'cleansheetsgk' => $clubPlayer->cleansheetsgk,
                    'goals' => $clubPlayer->goals,
                    'goalsconceded' => $clubPlayer->goalsconceded,
                    'losses' => $clubPlayer->losses,
                    'mom' => $clubPlayer->mom,
                    'passattempts' => $clubPlayer->passattempts,
                    'passesmade' => $clubPlayer->passesmade,
                    'pos' => $clubPlayer->pos,
                    'realtimegame' => $clubPlayer->realtimegame,
                    'realtimeidle' => $clubPlayer->realtimeidle,
                    'redcards' => $clubPlayer->redcards,
                    'saves' => $clubPlayer->saves,
                    'SCORE' => $clubPlayer->SCORE,
                    'shots' => $clubPlayer->shots,
                    'tackleattempts' => $clubPlayer->tackleattempts,
                    'tacklesmade' => $clubPlayer->tacklesmade,
                    'vproattr' => self::getProAttributes($clubPlayer->vproattr),
                    'vprohackreason' => $clubPlayer->vprohackreason,
                    'wins' => $clubPlayer->wins,
                    'playername' => $clubPlayer->playername,
                    'properties' => $clubPlayer,
                ];
            }
        }

        return $data;
    }

    private static function getProAttributes(string $attributes): string
    {
        return $attributes;
    }

    private static function getMatchOutcome(array $clubData): string
    {
        if ($clubData['wins'] == 1) {
            $outcome = 'homewin';
        } elseif ($clubData['losses'] == 1) {
            $outcome = 'awaywin';
        } elseif ($clubData['ties'] == 1) {
            $outcome = 'draw';
        }

        return $outcome;
    }

    public static function generateInsertData(array $result, string $platform): array
    {
        $carbonDate = Carbon::now();
        $carbonDate->timestamp($result['timestamp']);
        $clubs = collect($result['clubs'])->values();

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
