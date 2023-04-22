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
    public function it_can_create_a_single_player_and_confirm_they_exist_in_the_database()
    {
        Player::factory()->create();
        $this->assertDatabaseCount('players', 1);
    }

    /**
     * @test
     */
    public function it_can_create_multiple_players_and_confirm_they_exist_in_the_database()
    {
        Player::factory(10)->create();
        $this->assertDatabaseCount('players', 10);
    }

    /**
     * @test
     */
    public function it_can_test_player_model_exists_in_the_database()
    {
        $player = Player::factory()->create();
        $this->assertModelExists($player);
    }

    /**
     * @test
     */
    public function it_can_create_a_player_and_check_their_clubId_and_platform_and_name_is_correct()
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
    public function it_can_generate_an_type_error_exception_when_creating_a_player_and_incorrect_clubId_values_passed()
    {
        $this->expectException(TypeError::class);
        Player::factory()->create([
            'club_id' => [],
        ]);
    }

    /**
     * @test
     */
    public function it_can_generate_an_query_error_exception_when_creating_a_player_and_incorrect_platform_values_passed()
    {
        $this->expectException(QueryException::class);
        Player::factory()->create([
            'platform' => 'gameboy',
        ]);
    }

    /**
     * @test
     */
    public function it_can_generate_an_array_to_string_error_exception_when_creating_a_player_and_incorrect_attributes_values_passed()
    {
        $this->expectExceptionMessage('Array to string conversion');

        // Wrap the Player::factory()->create() call in a closure to trigger the exception when the closure is executed
        (function () {
            Player::factory()->create([
                'attributes' => [],
            ]);
        })();
    }

    /**
     * @test
     */
    public function it_can_create_a_player_and_check_findByClubAndPlatformAndPlayerName_scope_finds_the_correct_player()
    {
        // Create a test player in the database
        $player = Player::factory()->create([
            'club_id' => 1,
            'platform' => 'ps4',
            'player_name' => 'JohnDoe',
        ]);

        // Call the Scope method being tested
        $foundPlayer = Player::findByClubAndPlatformAndPlayerName(1, 'ps4', 'JohnDoe');

        $this->assertEquals($player->id, $foundPlayer->id);
        $this->assertEquals($player->club_id, $foundPlayer->club_id);
        $this->assertEquals($player->platform, $foundPlayer->platform);
        $this->assertEquals($player->player_name, $foundPlayer->player_name);
    }

    /**
     * @test
     */
    public function it_can_create_players_and_check_findByClubAndPlatform_scope_finds_the_correct_players()
    {
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

        // Call the Scope method being tested
        $players = Player::findByClubAndPlatform(1, 'ps4');

        $this->assertCount(2, $players);
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
