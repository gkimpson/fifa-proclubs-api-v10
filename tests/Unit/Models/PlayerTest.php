<?php

namespace Tests\Unit\Models;

use App\Models\Player;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TypeError;

class PlayerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function testSinglePlayerCanBeAdded()
    {
        Player::factory()->create();
        $this->assertDatabaseCount('players', 1);
    }

    /**
     * @test
     */
    public function testMultiplePlayersCanBeAdded()
    {
        Player::factory(10)->create();
        $this->assertDatabaseCount('players', 10);
    }

    /**
     * @test
     */
    public function testModelExistsInTheDatabase()
    {
        $player = Player::factory()->create();
        $this->assertModelExists($player);
    }

    /**
     * @test
     */
    public function testPlayerCanBeAddedAndVerifiedExactlyInDatabase()
    {
        Player::factory()->create([
            'club_id' => 12345,
            'platform' => 'ps5',
            'player_name' => 'johndoe',
        ]);

        $this->assertDatabaseCount('players', 1);
        $this->assertDatabaseHas('players', [
            'club_id' => 12345,
            'platform' => 'ps5',
            'player_name' => 'johndoe',
        ]);
    }

    /**
     * @test
     */
    public function testTypeErrorExceptionReturnedIfClubIdValueIncorrect()
    {
        $this->expectException(TypeError::class);
        Player::factory()->create([
            'club_id' => [],
        ]);
    }

    /**
     * @test
     */
    public function testQueryExceptionErrorExceptionReturnedIfPlatformValueIncorrect()
    {
        $this->expectException(QueryException::class);
        Player::factory()->create([
            'platform' => 'gameboy',
        ]);
    }

    /**
     * @test
     */
    public function testArrayToStringErrorExceptionMessageReturnedIfAttributesValueIncorrect()
    {
        $this->expectExceptionMessage('Array to string conversion');

        // Wrap the Player::factory()->create() call in a closure
        // to trigger the exception when the closure is executed
        (function () {
            Player::factory()->create([
                'attributes' => [],
            ]);
        })();
    }

    /**
     * @test
     */
    public function findByClubAndPlatformAndPlayerName()
    {
        // Create a test player in the database
        $player = Player::factory()->create([
            'club_id' => 1,
            'platform' => 'ps4',
            'player_name' => 'JohnDoe',
        ]);

        $foundPlayer = Player::findByClubAndPlatformAndPlayerName(1, 'ps4', 'JohnDoe');

        // Assert that the returned player is the same as the test player
        $this->assertEquals($player->id, $foundPlayer->id);
        $this->assertEquals($player->club_id, $foundPlayer->club_id);
        $this->assertEquals($player->platform, $foundPlayer->platform);
        $this->assertEquals($player->player_name, $foundPlayer->player_name);
    }

    /**
     * @test
     */
    public function findByClubAndPlatform()
    {
        // Create some test players in the database
        Player::factory()->create([
            'club_id' => 1,
            'platform' => 'ps4',
            'player_name' => 'JohnDoe',
        ]);
        Player::factory()->create([
            'club_id' => 1,
            'platform' => 'ps4',
            'player_name' => 'JaneDoe',
        ]);

        // Call the method being tested
        $players = Player::findByClubAndPlatform(1, 'ps4');

        // Assert that the returned collection has the expected number of players
        $this->assertCount(2, $players);

        // Assert that the players are in the expected order
        $this->assertEquals('JaneDoe', $players->get(0)->player_name);
        $this->assertEquals('JohnDoe', $players->get(1)->player_name);
    }

    /**
     * @test
     */
    private function attributesStringIsValid()
    {
        $string = '057|069|042|055|083|062|067|099|053|060|020|020|045|020|025|017|020|020|015|040|040|020|059|015|020|020|015|030|035|091|097|095|097|097|';

        // Split the string into an array of values
        $values = explode('|', $string);

        // Validate that the array has 36 values
        $this->assertCount(36, $values);

        // Validate that each value is an integer between 0 and 99
        foreach ($values as $value) {
            $this->assertIsNumeric($value);
            $this->assertGreaterThanOrEqual(0, $value);
            $this->assertLessThanOrEqual(99, $value);
        }
    }
}
