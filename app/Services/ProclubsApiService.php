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
    public const REFERER = 'https://www.ea.com/';

    // TODO - add to an ENUM later
    public function __construct()
    {
    }

    public static function doExternalApiCall(?string $endpoint = null, array $params = [], bool $jsonDecoded = false, bool $isCLI = false)
    {
        try {
            $url = self::API_URL . $endpoint . '?' . http_build_query($params);
            $curl = curl_init();

            // KEEP THIS BLOCK JUST IN CASE IT NEEDS TO BE USED AGAIN
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                // CURLOPT_MAXREDIRS => 5,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
                // CURLOPT_CUSTOMREQUEST => 'GET',
                // CURLOPT_VERBOSE => false,
                CURLOPT_FAILONERROR => true,
                CURLOPT_REFERER => self::REFERER,
                CURLOPT_HTTPHEADER => [
                    'accept-language: en-US,en;q=0.9,pt-BR;q=0.8,pt;q=0.7',
                    'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36',
                ],
            ]);

//            // TODO - use the Laravel way for this just need to figure it out?!, keeping the block above because we all know EA like to mess about like they did before and locked out the ProClub Avengers!
//            curl_setopt_array($curl, [
//                CURLOPT_URL => $url,
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_TIMEOUT => 5,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
//                CURLOPT_HTTPHEADER => [
//                    'accept-language: en-US,en;q=0.9,pt-BR;q=0.8,pt;q=0.7',
//                    'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36',
//                    'Content-Type: application/json',
//                    'accept: application/json',
//                ],
//            ]);

            if (curl_exec($curl) === false || curl_errno($curl)) {
                echo App::environment(['local', 'staging']) ? 'Curl error: ' . curl_error($curl) : 'An unexpected error has occurred - try again later';
                Log::error('Curl request failed with last error: ' . curl_error($curl));
            } else {
                if ($isCLI) {
                    echo "Operation completed without any errors\n";
                }
            }

            $response = curl_exec($curl);
            curl_close($curl);

            return $jsonDecoded ? json_decode($response) : $response;
        } catch (Exception $e) {
            // do some logging...
            Log::error('API request failed with exception: ' . $e->getMessage());

            return 0;
        }
    }

    // TODO - use the Laravel method - seems to not work on the AWS server but works locally
    public static function doLaravelExternalApiCall(?string $endpoint = null, array $params = [], bool $jsonDecoded = false, bool $isCLI = false)
    {
        try {
            $url = self::API_URL . $endpoint;

            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/112.0',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, br',
            ])->get($url, $params);

            $response->onError(static function ($response) {
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
