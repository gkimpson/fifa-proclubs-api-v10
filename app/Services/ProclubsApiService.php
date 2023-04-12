<?php

namespace App\Services;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProclubsApiService
{
    public const API_URL = 'https://proclubs.ea.com/api/fifa/';

    // TODO - add to an ENUM later
    public function __construct()
    {
    }

    public static function doExternalApiCall(?string $endpoint = null, array $params = [], bool $jsonDecoded = false, bool $isCLI = false)
    {
        try {
            $url = self::API_URL . $endpoint;

            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/112.0',
                'Accept' =>	'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, br',
            ])->get($url, $params);

            $response->onError(static function($response) {
                echo App::environment('production')
                    ? 'An unexpected error has occurred - try again later' . PHP_EOL
                    : 'An error occurred: ' . $response->reason() . PHP_EOL;

                Log::error('Request failed with last error: ' . $response->reason());
            });

            if ($isCLI) {
                echo 'Operation completed without any errors' . PHP_EOL;
            }

            return $jsonDecoded ? $response->json() : $response;
        } catch (Exception $e) {
            // do some logging...
            Log::error('API request failed with exception: ' . $e->getMessage());

            return 0;
        }
    }

    public static function clubsInfo(Platforms $platform, int $clubId): mixed
    {
        return self::doExternalApiCall('clubs/info', [
            'platform' => $platform->name(),
            'clubIds' => $clubId,
        ]);
    }

    public static function matchStats(Platforms $platform, int $clubId, MatchTypes $matchType): mixed
    {
        return self::doExternalApiCall('clubs/matches', [
            'matchType' => $matchType->name(),
            'platform' => $platform->name(),
            'clubIds' => $clubId,
        ]);
    }

    public static function memberStats(Platforms $platform, int $clubId): mixed
    {
        return self::doExternalApiCall('members/stats', [
            'platform' => $platform->name(),
            'clubId' => $clubId,
        ]);
    }

    public static function careerStats(Platforms $platform, int $clubId): string
    {
        return self::doExternalApiCall('members/career/stats', [
            'platform' => $platform->name(),
            'clubId' => $clubId,
        ]);
    }

    public static function seasonStats(Platforms $platform, int $clubId): string
    {
        return self::doExternalApiCall('clubs/seasonalStats', [
            'platform' => $platform->name(),
            'clubIds' => $clubId,
        ]);
    }

    public static function settings(Platforms $platform, string $clubName): string
    {
        return self::doExternalApiCall('settings', [
            'platform' => $platform->name(),
            'clubName' => $clubName,
        ]);
    }

    public static function search(Platforms $platform, string $clubName): string
    {
        return self::doExternalApiCall('clubs/search', [
            'platform' => $platform->name(),
            'clubName' => $clubName,
        ]);
    }

    public static function leaderboard(Platforms $platform, string $type): string
    {
        $endpoint = $type === 'club' ? 'clubRankLeaderboard' : 'seasonRankLeaderboard';

        return self::doExternalApiCall($endpoint, [
            'platform' => $platform->name(),
        ]);
    }

    public static function playerStats(Platforms $platform, int $clubId, string $playerName): array
    {
        $career = json_decode(self::careerStats($platform, $clubId));
        $members = json_decode(self::memberStats($platform, $clubId));

        return [
            'career' => self::filterPlayer($career->members, $playerName),
            'members' => self::filterPlayer($members->members, $playerName),
        ];
    }

    private static function filterPlayer(array $players, string $playerName): object|bool
    {
        $targetPlayer = array_filter($players, function ($player) use ($playerName) {
            return $player->name === $playerName;
        });

        return reset($targetPlayer);
    }
}
