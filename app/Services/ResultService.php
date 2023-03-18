<?php

namespace App\Services;

use App\Enums\Platforms;
use Illuminate\Support\Facades\Cache;

class ResultService
{
    private const CACHE_TTL = 15;

    private ProclubsApiService $apiService;

    public function __construct(ProclubsApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->rankingTypes = $this->getRankingTypes();
    }

    public function getCachedData(int $clubId, string $platform, string $cacheName): object
    {
        $method = 'get' . ucfirst($cacheName) . 'Data';

        return $this->processCache($cacheName, $clubId, $platform, [$this, $method]);
    }

    public function getPlayerComparisonData(int $clubId, string $platform, string $player1, string $player2): array
    {
        if ($player1 === $player2) {
            abort(403, 'Cannot use the same player for comparison');
        }

        $careerData = $this->getCachedData($clubId, $platform, 'career');
        $membersData = $this->getCachedData($clubId, $platform, 'squad');

        return $this->generatePlayerComparisonData($careerData, $membersData, $player1, $player2);
    }

    public function getRankingData(int $clubId, string $platform): array
    {
        $members = $this->getCachedData($clubId, $platform, 'squad');
        $data = $this->mapRankingData($members, 'sortingRankingData');

        return array_merge(...$data);
    }

    public function getCustomRankingData(int $clubId, string $platform): array
    {
        $members = $this->getCachedData($clubId, $platform, 'squad');
        $data = $this->mapRankingData($members, 'sortingCustomRankingData');

        return array_merge(...$data);
    }

    private function getSquadData(int $clubId, string $platform): object
    {
        return json_decode($this->apiService::memberStats(Platforms::getPlatform($platform), $clubId));
    }

    private function getCareerData(int $clubId, string $platform): object
    {
        return json_decode($this->apiService::careerStats(Platforms::getPlatform($platform), $clubId));
    }

    /**
     * Retrieve cached data or fetch and cache new data using the specified cache name and expiration time.
     *
     * @param  string  $cacheName name of the cache key to use.
     * @param  mixed  $clubId unique identifier for the club
     * @param  string  $platform platform/console
     * @param  callable  $dataGetter function that retrieves data for the specified club and platform.
     * @return mixed The cached data or new data retrieved by the dataGetter function.
     */
    private function processCache(string $cacheName, int $clubId, string $platform, array $dataGetter)
    {
        return Cache::remember($cacheName, self::CACHE_TTL, function () use ($clubId, $platform, $dataGetter) {
            return $dataGetter($clubId, $platform);
        });
    }

    private function filterPlayerData(object $players, string $matchedPlayer)
    {
        if (isset($players->members)) {
            $targetPlayer = collect($players->members)
                ->first(function ($player) use ($matchedPlayer) {
                    return $player->name === $matchedPlayer;
                });

            return $targetPlayer;
        }
    }

    private function generatePlayerComparisonData(object $careerData, object $membersData, string $player1, string $player2): array
    {
        return [
            'player1' => $this->getPlayerData($careerData, $membersData, $player1),
            'player2' => $this->getPlayerData($careerData, $membersData, $player2),
        ];
    }

    private function getPlayerData(object $careerData, object $membersData, string $player): array
    {
        return [
            'career' => $this->filterPlayerData($careerData, $player),
            'members' => $this->filterPlayerData($membersData, $player),
        ];
    }

    private function mapRankingData(object $members, string $sortingMethod): array
    {
        return array_map(function ($rankingType) use ($members, $sortingMethod) {
            return [$rankingType => $this->{$sortingMethod}($rankingType, $members)];
        }, $this->rankingTypes);
    }

    private function sortingCustomRankingData(string $rankingType, object $membersObject): array
    {
        $membersCollection = collect($membersObject->members ?? []);

        return $membersCollection->filter(function ($item) use ($rankingType) {
            return isset($item->$rankingType) && $item->gamesPlayed > 0 && $item->$rankingType > 0;
        })
            ->mapWithKeys(function ($item) use ($rankingType) {
                $gamesPlayed = (int) $item->gamesPlayed;
                $rankingTypeValue = (int) $item->$rankingType;

                return [$item->name => $rankingTypeValue / $gamesPlayed];
            })
            ->sortDesc()
            ->toArray();
    }

    private function sortingRankingData(string $rankingType, object $membersObject): array
    {
        $membersCollection = collect($membersObject->members ?? []);

        return $membersCollection->sortByDesc($rankingType)
            ->pluck($rankingType, 'name')
            ->toArray();
    }

    private function getRankingTypes(): array
    {
        return [
            'assists',
            'cleanSheetsDef',
            'cleanSheetsGk',
            'favoritePosition',
            'gamesPlayed',
            'goals',
            'manOfTheMatch',
            'passSuccessRate',
            'passesMade',
            'prevGoals',
            'proHeight',
            'proOverall',
            'ratingAve',
            'shotSuccessRate',
            'tackleSuccessRate',
            'tacklesMade',
            'winRate',
        ];
    }
}
