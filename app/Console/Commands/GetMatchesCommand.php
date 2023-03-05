<?php

namespace App\Console\Commands;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use App\Models\Result;
use App\Models\User;
use App\Services\ProClubsApiService;
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
            ray()->measure();
            $this->info('Running...'.$this->description);
            $properties = User::pluck('platform', 'club_id')->unique();

            foreach ($properties as $clubId => $platform) {
                $this->info("Collecting matches data for - Platform: {$platform} | ClubId: {$clubId}");
                $leagueResults = ProClubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::LEAGUE);
                $cupResults = ProClubsApiService::matchStats(Platforms::getPlatform($platform), $clubId, MatchTypes::CUP);
                $results = array_merge(Result::formatJsonData($leagueResults), Result::formatJsonData($cupResults));
                $count = count($results);
                $this->info("{$count} matches found");
                $inserted = Result::insertMatches($results, $platform);
                $this->info("{$inserted} unique results into the database");
            }
            ray()->measure();

            return 0;
        } catch (\Exception $e) {
            log::error($e->getMessage());

            return 0;
        }
    }
}
