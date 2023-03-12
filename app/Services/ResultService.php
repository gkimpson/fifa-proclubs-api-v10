<?php

namespace App\Services;

use App\Enums\Platforms;

class ResultService
{
    public function __construct()
    {

    }

    public function getSquadData(int $clubId, string $platform)
    {
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
            $targetPlayer = array_filter($collection['members'], function($player) use ($matchedPlayer) {
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
                'members' => $this->filterPlayerData($membersData, $player1)
            ],
            'player2' => [
                'career' => $this->filterPlayerData($careerData, $player2),
                'members' => $this->filterPlayerData($membersData, $player2)
            ],
        ];
    }

}
