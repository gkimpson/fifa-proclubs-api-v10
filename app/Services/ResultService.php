<?php

namespace App\Services;

use App\Enums\Platforms;
use Illuminate\Support\Facades\Cache;

class ResultService
{
    public function __construct()
    {

    }

    public function getSquadData(int $clubId, string $platform)
    {
        sleep(10);
        return json_decode(ProClubsApiService::memberStats(Platforms::getPlatform($platform), $clubId));
    }

    public function getCareerData(int $clubId, string $platform)
    {
        return json_decode(ProClubsApiService::careerStats(Platforms::getPlatform($platform), $clubId));
    }

    public function getPlayerComparisonData(int $clubId, string $platform, string $player1, string $player2)
    {
        if ($player1 === $player2) {
            abort(403, 'Cannot use the same player for comparison');
        }

        $careerData = $this->getCareerData($clubId, $platform);
        $membersData = $this->getSquadData($clubId, $platform);

        return $this->generatePlayerComparisonData($careerData, $membersData, $player1, $player2);
    }

    private function filterPlayerData(object $players, string $matchedPlayer)
    {
        $collection = collect($players);

        if ($collection->has('members')) {
            $targetPlayer = array_filter($collection['members'], function ($player) use ($matchedPlayer) {
                return $player->name === $matchedPlayer;
            });

            return reset($targetPlayer);
        }

        return StdClass();
    }

    private function generatePlayerComparisonData(object $careerData, object $membersData, string $player1, string $player2)
    {
        return [
            'player1' => [
                'career' => $this->filterPlayerData($careerData, $player1),
                'members' => $this->filterPlayerData($membersData, $player1),
            ],
            'player2' => [
                'career' => $this->filterPlayerData($careerData, $player2),
                'members' => $this->filterPlayerData($membersData, $player2),
            ],
        ];
    }

    /*
     * TODO - setup another cache driver
     */
    private function processSquadDataCache($clubId, $platform)
    {
        $cacheName = 'squadData';
        if (Cache::has($cacheName)) {
            $members = Cache::get($cacheName);
        } else {
            $members = $this->getSquadData($clubId, $platform);
            Cache::put($cacheName, $members, 30);
        }

        return $members;
    }

    public function getRankingData(int $clubId, string $platform): array
    {
        $members = $this->processSquadDataCache($clubId, $platform);

        $data = array_map(function($rankingType) use ($members) {
            return [$rankingType => $this->sortingRankingData($rankingType, $members)];
        }, $this->rankingTypes());

        return array_merge(...$data);
    }

    public function getCustomRankingData(int $clubId, string $platform): array
    {
        $members = $this->processSquadDataCache($clubId, $platform);
        $data = array_map(function($rankingType) use ($members) {
            return [$rankingType => $this->sortingCustomRankingData($rankingType, $members)];
        }, $this->rankingTypes());

        return array_merge(...$data);
    }

    private function sortingCustomRankingData(string $rankingType, object $data): array
    {
        $members = $data->members ?? [];
        $membersCollection = collect($members);

        return $membersCollection->mapWithKeys(function ($item) use ($rankingType) {
            if (!isset($item->$rankingType)) return [];
            $gamesPlayed = (int) $item->gamesPlayed;
            $rankingTypeValue = (int) $item->$rankingType;
            if ($item->gamesPlayed == 0 || $rankingTypeValue == 0) return [];
            return [$item->name => $rankingTypeValue / $gamesPlayed];
        })->sort()->reverse()->toArray();
    }

    private function sortingRankingData(string $rankingType, object $data): array
    {
        $members = $data->members ?? [];
        $membersCollection = collect($members);
        return $membersCollection->sortByDesc($rankingType)
            ->pluck($rankingType, 'name')
            ->toArray();
    }

    /**
     * TODO - add to enum, config perhaps??
     */
    private function rankingTypes()
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
