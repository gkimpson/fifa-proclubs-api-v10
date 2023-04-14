<?php

namespace App\Console\Commands;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use App\Models\Player;
use App\Models\PlayerAttribute;
use App\Models\Result;
use App\Models\ResultDataFormatter;
use App\Models\User;
use App\Services\ProclubsApiService;
use App\Services\ResultService;
use Assert\Assertion;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class GetMatchesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proclubs:matches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get matches';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {
            $this->info('Running...' . $this->description);

            $properties = $this->getDistinctClubIdAndPlatform();
            $this->processClubProperties($properties);

            return 0;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return 0;
        }
    }

    protected function fetchMatchResults(string $platform, int $clubId): array
    {
        $leagueResults = ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::LEAGUE);
        $cupResults = ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::CUP);

        return array_merge(ResultDataFormatter::formatJsonData($leagueResults), ResultDataFormatter::formatJsonData($cupResults));
    }

    protected function storeMatchResults(array $results, string $platform): void
    {
        $count = count($results);
        $this->info("{$count} matches found");

        $inserted = ResultService::insertMatches($results, $platform);
        $this->info("{$inserted} unique results into the database");
    }

    protected function updatePlayers(int $clubId, string $platform): void
    {
        $latestResult = Result::byTeam($clubId);
        $players = collect($latestResult->properties['players'][$clubId]);

        $players->each(function (array $row, $eaPlayerId) use ($clubId, $platform) {
            Assertion::length($row['vproattr'], 136, 'Invalid vproattr length, 136 string length expected however "%s" given');

            $player = $this->updateOrCreatePlayer($clubId, $platform, $row, $eaPlayerId);
            $attributes = $this->generatePlayerAttributes($row);
            PlayerAttribute::updateOrCreate(
                ['player_id' => $player->id],
                $attributes
            );

            $this->info('Player updated: ' . $player->player_name);
        });
    }

    private function getDistinctClubIdAndPlatform(): \Illuminate\Support\Collection
    {
        return User::distinct()->pluck('platform', 'club_id');
    }

    private function processClubProperties(object $properties): void
    {
        $properties->map(function ($platform, $clubId) use ($properties) {
            $this->info("Collecting matches data for - Platform: {$platform} | ClubId: {$clubId}");
            $lastIteration = ($clubId === $properties->keys()->last());

            $results = $this->fetchMatchResults($platform, $clubId);
            $this->storeMatchResults($results, $platform);

            if ($lastIteration) {
                $this->updatePlayers($clubId, $platform);
            }
        });
    }

    private function updateOrCreatePlayer(int $clubId, string $platform, array $row, int $eaPlayerId): Player
    {
        return Player::updateOrCreate(
            ['club_id' => $clubId, 'platform' => $platform, 'player_name' => $row['playername']],
            ['ea_player_id' => $eaPlayerId, 'attributes' => $row['vproattr']]
        );
    }

    private function generatePlayerAttributes(array $row): array
    {
        $attributes = PlayerAttribute::generateAttributes($row['vproattr']);
        $favouritePosition = PlayerAttribute::generateFavouritePosition($row['pos']);
        if ($favouritePosition) {
            $attributes = Arr::add($attributes, 'favourite_position', $favouritePosition);
        }

        return $attributes;
    }
}
