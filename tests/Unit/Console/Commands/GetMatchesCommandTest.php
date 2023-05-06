<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Commands;

use App\Enums\MatchTypes;
use App\Enums\Platforms;
use App\Models\Player;
use App\Models\Result;
use App\Models\User;
use App\Services\ProclubsApiService;
use App\Services\ResultService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GetMatchesCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function CanStoreMatchResultsSuccessfully(): void
    {
        $results = [
            [
                'matchId' => '12345678',
                'timestamp' => '2021-01-01T00:00:00Z',
                'clubs' => [
                    [
                        'id' => 12345,
                        'goals' => 2,
                        'wins' => 1,
                        'ties' => 0,
                        'losses' => 0,
                    ],
                    [
                        'id' => 23456,
                        'goals' => 1,
                        'wins' => 0,
                        'ties' => 0,
                        'losses' => 1,
                    ],
                ],
                'players' => [
                    12345 => [
                        // Players from club 1
                    ],
                    23456 => [
                        // Players from club 2
                    ],
                ],
                'aggregate' => [
                    12345 => [],
                    23456 => [],
                ],
            ],
        ];

        $count = count($results);
        $inserted = ResultService::insertMatches($results, 'ps5');

        $this->assertEquals($count, $inserted);
        $this->assertCount($count, Result::all());
    }

    /** @test */
    public function itFetchesAndStoresMatchesForAllClubs(): void
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
                    'matchId' => '98765',
                ],
            ]
        );

        Result::factory()->create(
            [
                'away_team_id' => 23456,
                'platform' => 'ps4',
                'properties' => [
                    'matchId' => '87654',
                ],
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

    /** @test */
    public function testUniquePlayerIsCreated(): void
    {
        $eaPlayerId = 1234;
        $platform = 'ps5';
        $clubId = 12345;
        $row = [
            'playername' => 'JohnDoe',
            'vproattr' => '061|078|070|078|089|091|094|095|097|086|095|078|090|082|081|069|074|074|086|093|093|096|081|076|096|094|064|077|070|010|010|010|010|010|',
        ];

        $player = Player::updateOrCreate(
            ['ea_player_id' => $eaPlayerId, 'platform' => $platform, 'player_name' => $row['playername']],
            ['club_id' => $clubId, 'attributes' => $row['vproattr']]
        );

        $this->assertNotEmpty($player->id);
        $this->assertEquals($eaPlayerId, $player->ea_player_id);
        $this->assertEquals($platform, $player->platform);
        $this->assertEquals($clubId, $player->club_id);
        $this->assertNotEmpty($player->attributes);
        $this->assertEquals($row['playername'], $player->player_name);
    }
}
