<?php

namespace App\Console\Commands;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use App\Models\Player;
use App\Models\PlayerAttribute;
use App\Models\Result;
use App\Models\User;
use App\Services\ProclubsApiService;
use Assert\Assertion;
use Exception;
use Illuminate\Console\Command;
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

            // Get distinct club_id & platform properties
            $properties = User::distinct()->pluck('platform', 'club_id');

            $properties->map(function ($platform, $clubId) use ($properties) {
                $this->info("Collecting matches data for - Platform: {$platform} | ClubId: {$clubId}");
                $lastIteration = ($clubId === $properties->keys()->last());

                $leagueResults = ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::LEAGUE);
                $cupResults = ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::CUP);
                $results = array_merge(Result::formatJsonData($leagueResults), Result::formatJsonData($cupResults));

                $count = count($results);
                $this->info("{$count} matches found");

                $inserted = Result::insertMatches($results, $platform);
                $this->info("{$inserted} unique results into the database");

                // Check if this is the last iteration and add/update players for YOUR club (maybe extend this to all clubs?) // TODO - Add/update players for all clubs later
                if ($lastIteration) {
                    $latestResult = Result::byTeam($clubId);
                    $players = collect($latestResult->properties['players'][$clubId]);
                    $players->each(function (array $row, $eaPlayerId) use ($clubId, $platform) {
                        Assertion::length($row['vproattr'], 136, 'Invalid vproattr length, 136 string length expected however "%s" given');
                        $player = Player::updateOrCreate(
                            ['club_id' => $clubId, 'platform' => $platform, 'player_name' => $row['playername']],
                            ['ea_player_id' => $eaPlayerId, 'attributes' => $row['vproattr']]
                        );

                        $attributes = PlayerAttribute::generateAttributes($row['vproattr']);
                        PlayerAttribute::updateOrCreate(
                            ['player_id' => $player->id],
                            $attributes
                        );
                        $this->info('Player updated: ' . $player->player_name);
                    });
                }
            });

            return 0;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return 0;
        }
    }
}
