<?php

namespace App\Models;

use App\Enums\Outcomes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ResultDataFormatter
{
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
            'SCORE' => $player->SCORE,
            'assists' => $player->assists,
            'cleansheetsany' => $player->cleansheetsany,
            'cleansheetsdef' => $player->cleansheetsdef,
            'cleansheetsgk' => $player->cleansheetsgk,
            'goals' => $player->goals,
            'goalsconceded' => $player->goalsconceded,
            'losses' => $player->losses,
            'mom' => $player->mom,
            'namespace' => $player->namespace,
            'passattempts' => $player->passattempts,
            'passesmade' => $player->passesmade,
            'playername' => $player->playername,
            'pos' => $player->pos,
            'rating' => $player->rating,
            'realtimegame' => $player->realtimegame,
            'realtimeidle' => $player->realtimeidle,
            'redcards' => $player->redcards,
            'saves' => $player->saves,
            'shots' => $player->shots,
            'tackleattempts' => $player->tackleattempts,
            'tacklesmade' => $player->tacklesmade,
            'vproattr' => $player->vproattr,
            'vprohackreason' => $player->vprohackreason,
            'wins' => $player->wins,
            //            'properties' => $player,
        ];
    }

    public static function getMatchOutcome(array $clubData): string
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
            'outcome' => ResultDataFormatter::getMatchOutcome($clubs[0]),
            'match_date' => $carbonDate->format('Y-m-d H:i:s'),
            'properties' => [
                'clubs' => $result['clubs'],
                'players' => $result['players'],
                'aggregate' => $result['aggregate'], // aggregate is used for consistency as EA use the same naming convention - this is basically 'team stats' for that match
            ],
            'platform' => $platform,
        ];
    }
}
