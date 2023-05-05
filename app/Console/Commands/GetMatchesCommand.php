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
    protected $signature = 'proclubs:matches {useLaravelHttp?}';   // optional argument

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
            $useLaravelHttp = (bool) $this->argument('useLaravelHttp') ?? false;

            $properties = $this->getDistinctClubIdAndPlatform();
            $this->processClubProperties($properties, $useLaravelHttp);

            return 0;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return 0;
        }
    }

    public function fetchMatchResults(string $platform, int $clubId, bool $useLaravelHttp = false): array
    {
        $leagueResults = ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::LEAGUE, $useLaravelHttp);
        $cupResults = ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::CUP, $useLaravelHttp);

        return array_merge(ResultDataFormatter::formatJsonData($leagueResults), ResultDataFormatter::formatJsonData($cupResults));
    }

    public function storeMatchResults(array $results, string $platform): void
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

    private function processClubProperties(object $properties, bool $useLaravelHttp = false): void
    {
        $properties->map(function ($platform, $clubId) use ($properties, $useLaravelHttp) {
            $this->info("Collecting matches data for - Platform: {$platform} | ClubId: {$clubId}");
            $lastIteration = ($clubId === $properties->keys()->last());

            $results = $this->fetchMatchResults($platform, $clubId, $useLaravelHttp);
            $this->storeMatchResults($results, $platform);

            if ($lastIteration) {
                $this->updatePlayers($clubId, $platform);
            }
        });
    }

    private function updateOrCreatePlayer(int $clubId, string $platform, array $row, int $eaPlayerId): Player
    {
        return Player::updateOrCreate(
            ['ea_player_id' => $eaPlayerId, 'platform' => $platform, 'player_name' => $row['playername']],
            ['club_id' => $clubId, 'attributes' => $row['vproattr']]
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
