<?php

namespace Tests\Unit\Scopes;

use App\Models\Result;
use App\Models\User;
use App\Scopes\ClubScope;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClubScopeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itAppliesClubScopeToResultModel(): void
    {
        // Create test data
        $user = User::factory()->create(['club_id' => 1]);
        User::factory()->create(['club_id' => 2]);

        $this->actingAs($user);

        // Create matches with different club ids
        $result1 = Result::factory()->create(['home_team_id' => 1, 'away_team_id' => 1000]);
        $result2 = Result::factory()->create(['home_team_id' => 2, 'away_team_id' => 2000]);
        $result3 = Result::factory()->create(['home_team_id' => 3, 'away_team_id' => 3000]);

        // Apply the scope to the match query builder
        $results = Result::query()->withGlobalScope('club_id', new ClubScope)->get();

        // Assert that only the matches that involve the user's club are returned
        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($result1));
        $this->assertFalse($results->contains($result2));
        $this->assertFalse($results->contains($result3));
    }
}
