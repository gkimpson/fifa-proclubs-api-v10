<?php


namespace Tests\Unit\Console\Commands;

use App\Console\Commands\GetMatchesCommand;
use App\Enums\MatchTypes;
use App\Enums\Platforms;
use App\Models\Player;
use App\Models\Result;
use App\Models\User;
use App\Services\ProclubsApiService;
use App\Services\ResultService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Mockery;

class GetMatchesCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_and_stores_matches_for_all_clubs()
    {
        // Mock HTTP responses
        Http::fake([
            ProclubsApiService::matchStats(Platforms::PC, 12345, MatchTypes::LEAGUE) => Http::response(['some' => 'data']),
            ProclubsApiService::matchStats(Platforms::PC, 23456, MatchTypes::CUP) => Http::response(['more' => 'data']),
        ]);

        // Create test data
        $user1 = User::factory()->create(['platform' => 'pc', 'club_id' => 12345]);
        $user2 = User::factory()->create(['platform' => 'ps4', 'club_id' => 23456]);

        Result::factory()->create(
            [
                'home_team_id' => 12345,
                'platform' => 'pc',
                'properties' => [
                    'matchId' => '98765'
                ]
            ]
        );

        Result::factory()->create(
            [
                'away_team_id' => 23456,
                'platform' => 'ps4',
                'properties' => [
                    'matchId' => '87654'
                ]
            ]
        );

        // Check the results were stored for each club
        $this->assertCount(2, Result::all());
        $result1 = Result::where('home_team_id', $user1->club_id)->first();
        $result2 = Result::where('away_team_id', $user2->club_id)->first();

        $this->assertEquals($user1->club_id, $result1->home_team_id);
        $this->assertEquals($user1->platform, $result1->platform);
        $this->assertNotEmpty($result1->properties);
        $this->assertEquals($user2->club_id, $result2->away_team_id);
        $this->assertEquals($user2->platform, $result2->platform);
        $this->assertNotEmpty($result2->properties);
    }
}
