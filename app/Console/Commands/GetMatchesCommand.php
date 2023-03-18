<?php

namespace App\Console\Commands;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use App\Models\Result;
use App\Models\User;
use App\Services\ProclubsApiService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
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
    public function handle(Request $request): int
    {
        try {
//            ray()->measure();
            $this->info('Running...' . $this->description);

            // Get distinct club_id & platform properties
            $properties = User::distinct()->pluck('platform', 'club_id');

            foreach ($properties as $clubId => $platform) {
                $this->info("Collecting matches data for - Platform: {$platform} | ClubId: {$clubId}");

                // Get league and cup results, then combine them
                $leagueResults = ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::LEAGUE);
                $cupResults = ProclubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::CUP);
                $results = array_merge(Result::formatJsonData($leagueResults), Result::formatJsonData($cupResults));

                $this->info(json_encode($results));
                $count = count($results);
                $this->info("{$count} matches found");

                // Insert results
                $inserted = Result::insertMatches($results, $platform);
                $this->info("{$inserted} unique results into the database");
            }
//            ray()->measure();
            return 0;
        } catch (Exception $e) {
            log::error($e->getMessage());

            return 0;
        }
    }
}
