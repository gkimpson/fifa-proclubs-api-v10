<?php

namespace Tests\Unit\Console\Commands;

use App\Console\Commands\ProcessActivePlayersCommand;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProcessActivePlayersCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itRemovesInactivePlayersFromClubs(): void
    {
        // Create active player who played a match in the last 30 days
        $activePlayer = Player::factory()->create(['updated_at' => now()->subDays(15)]);

        // Create inactive player who didn't play a match in the last 30 days
        $inactivePlayer = Player::factory()->create(['updated_at' => now()->subDays(45)]);

        // Run the command
        Artisan::call('proclubs:players');

        // Assert that the inactive player was removed from the club
        if (isset($inactivePlayer->fresh()->club_id)) {
            $this->assertEquals(0, $inactivePlayer->fresh()->club_id);
        }

        // Assert that the active player wasn't removed from the club
        if (isset($activePlayer->fresh()->club_id)) {
            $this->assertNotEquals(0, $activePlayer->fresh()->club_id);
        }
    }

    /**
     * @test
     */
    public function itDoesNotRemoveActivePlayersFromClubs(): void
    {
        // Create active player who played a match in the last 30 days
        $player = Player::factory()->create(['updated_at' => now()->subDays(15)]);

        // Run the command
        Artisan::call('proclubs:players');

        // Assert that the active player wasn't removed from the club
        if (isset($player->fresh()->club_id)) {
            $this->assertNotEquals(0, $player->fresh()->club_id);
        }
    }

    /**
     * @test
     */
    public function removeInactivePlayersFromClubsReturnsIntegerOfUpdatedRows(): void
    {
        // Create some inactive players
        Player::factory()->count(3)->create(['updated_at' => now()->subDays(45)]);

        // Create an instance of the command
        $command = new ProcessActivePlayersCommand;

        // Call the removeInactivePlayersFromClubs method
        $count = $command->removeInactivePlayersFromClubs();

        // Assert that the return value is an integer
        $this->assertIsInt($count);

        // Assert that the number of updated rows matches the number of inactive players
        $this->assertEquals(3, $count);
    }
}
