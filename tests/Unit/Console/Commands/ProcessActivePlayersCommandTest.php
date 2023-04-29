<?php

namespace Tests\Unit\Console\Commands;

use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProcessActivePlayersCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_removes_inactive_players_from_clubs()
    {
        // Create active player who played a match in the last 30 days
        $activePlayer = Player::factory()->create(['updated_at' => now()->subDays(15)]);

        // Create inactive player who didn't play a match in the last 30 days
        $inactivePlayer = Player::factory()->create(['updated_at' => now()->subDays(45)]);

        // Run the command
        Artisan::call('proclubs:players');

        // Assert that the inactive player was removed from the club
        $this->assertEquals(0, $inactivePlayer->fresh()->club_id);

        // Assert that the active player wasn't removed from the club
        $this->assertNotEquals(0, $activePlayer->fresh()->club_id);
    }

    public function test_it_does_not_remove_active_players_from_clubs()
    {
        // Create active player who played a match in the last 30 days
        $player = Player::factory()->create(['updated_at' => now()->subDays(15)]);

        // Run the command
        Artisan::call('proclubs:players');

        // Assert that the active player wasn't removed from the club
        $this->assertNotEquals(0, $player->fresh()->club_id);
    }
}
