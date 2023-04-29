<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;

class ProcessActivePlayersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proclubs:players';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process active players (remove players who have not played a match for the associated club in the last 30 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running: ' . $this->description);
        $count = $this->removeInactivePlayersFromClubs();
        $this->info("Finished: {$this->description}. Updated $count players.");
    }

    /**
     * TODO: temporary solution to remove players who aren't 'active' for their club
     * Remove players who have not played a match for the associated club in the last 30 days
     * We don't want to remove players who have played a match for their club in the last 30 days as some players may be
     * 'inactive' for a period of time but then return to the club e.g a week later...
     * @return int
     */
    protected function removeInactivePlayersFromClubs(): int
    {
        return Player::where('updated_at', '<=', now()->subDays(30))
            ->update(['club_id' => 0]);
    }
}
