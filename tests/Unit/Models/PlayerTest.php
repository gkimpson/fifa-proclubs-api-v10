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
    public function itCanCreateASinglePlayerAndConfirmTheyExistInTheDatabase(): void
    {
        Player::factory()->create();
        $this->assertDatabaseCount('players', 1);
    }

    /**
     * @test
     */
    public function itCanCreateMultiplePlayersAndConfirmTheyExistInTheDatabase(): void
    {
        Player::factory(10)->create();
        $this->assertDatabaseCount('players', 10);
    }

    /**
     * @test
     */
    public function itCanTestPlayerModelExistsInTheDatabase(): void
    {
        $player = Player::factory()->create();
        $this->assertModelExists($player);
    }

    /**
     * @test
     */
    public function itCanCreateAPlayerAndCheckTheirClubIdAndPlatformAndNameIsCorrect(): void
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
    public function itCanGenerateAnTypeErrorExceptionWhenCreatingAPlayerAndIncorrectClubIdValuesPassed(): void
    {
        $this->expectException(TypeError::class);
        Player::factory()->create([
            'club_id' => [],
        ]);
    }

    /**
     * @test
     */
    public function itCanGenerateAnQueryErrorExceptionWhenCreatingAPlayerAndIncorrectPlatformValuesPassed(): void
    {
        $this->expectException(QueryException::class);
        Player::factory()->create([
            'platform' => 'gameboy',
        ]);
    }

    /**
     * @test
     */
    public function itCanGenerateArrayToStringErrorExceptionWhenCreatingAPlayerAndIncorrectAttributesValuesPassed():void
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
    public function itCanCreateAPlayerAndCheckFindByClubAndPlatformAndPlayerNameScopeFindsTheCorrectPlayer(): void
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
    public function itCanCreatePlayersAndCheckFindByClubAndPlatformScopeFindsTheCorrectPlayers(): void
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
    public function itCanGenerateValidAttributesArrayFromString(): void
    {
        // phpcs:ignore Generic.Files.LineLength.TooLong
        $string = '057|069|88|042|055|083|062|067|099|053|060|020|020|045|020|025|017|020|020|015|040|040|020|059|015|020|020|015|030|035|091|097|095|097|097|';

        // Split the string into an array of values
        $values = explode('|', $string);

        // Validate that the array has 36 values
        $this->assertCount(36, $values);

        // Validate that each value is an integer between 0 and 99
        foreach ($values as $value) {
            if (!empty($value)) {
                $this->assertIsNumeric($value);
                $this->assertGreaterThanOrEqual(0, $value);
                $this->assertLessThanOrEqual(99, $value);
            }
        }
    }
}
