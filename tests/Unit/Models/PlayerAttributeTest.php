<?php

namespace Tests\Unit\Models;

use App\Models\Player;
use App\Models\PlayerAttribute;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use ReflectionClass;
use Tests\TestCase;

class PlayerAttributeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return int[]
     */
    public function realisticPlayerAttributes(): array
    {
        return [
            80, 75, 90, 80, 70, 70, 65, 85, 80, 75,
            70, 75, 80, 65, 60, 65, 70, 75, 70, 75,
            80, 75, 80, 70, 65, 75, 75, 70, 70, 80,
            70, 65, 80, 75
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->attributes =
            '80|75|90|80|70|70|65|85|80|75|70|75|80|65|60|65|70|75|70|75|80|75|80|70|65|75|75|70|70|80|70|65|80|75';
    }

    /** @test */
    public function itCanGenerateAFavouritePositionForGoalkeeper(): void
    {
        $position = 'goalkeeper';

        $favouritePosition = PlayerAttribute::generateFavouritePosition($position);

        $this->assertEquals('G', $favouritePosition);
    }

    /** @test */
    public function itCanGenerateAFavouritePositionForDefender(): void
    {
        $position = 'defender';

        $favouritePosition = PlayerAttribute::generateFavouritePosition($position);

        $this->assertEquals('D', $favouritePosition);
    }

    /** @test */
    public function itCanGenerateAFavouritePositionForMidfielder(): void
    {
        $position = 'midfielder';

        $favouritePosition = PlayerAttribute::generateFavouritePosition($position);

        $this->assertEquals('M', $favouritePosition);
    }

    /** @test */
    public function itCanGenerateAFavouritePositionForForward(): void
    {
        $position = 'forward';

        $favouritePosition = PlayerAttribute::generateFavouritePosition($position);

        $this->assertEquals('F', $favouritePosition);
    }

    /** @test */
    public function itReturnsNullWhenGeneratingAFavouritePositionWithAnEmptyString(): void
    {
        $favouritePosition = PlayerAttribute::generateFavouritePosition('');

        $this->assertNull($favouritePosition);
    }

    /** @test */
    public function itThrowsAnExceptionWhenGeneratingAFavouritePositionWithAnInvalidPosition(): void
    {
        $this->expectException(InvalidArgumentException::class);

        PlayerAttribute::generateFavouritePosition('invalid position');
    }

    /** @test */
    public function itCanParseAttributes(): void
    {
        $parsedAttributes = PlayerAttribute::parseAttributes($this->attributes);

        $this->assertEquals($this->realisticPlayerAttributes(), $parsedAttributes->toArray());
    }

    /** @test */
    public function itCanGenerateAttributes(): void
    {
        $generatedAttributes = PlayerAttribute::generateAttributes($this->attributes);
        $this->assertEquals([
            'acceleration' => 80,
            'sprint_speed' => 75,
            'agility' => 90,
            'balance' => 80,
            'jumping' => 70,
            'stamina' => 70,
            'strength' => 65,
            'reactions' => 85,
            'aggression' => 80,
            'unsure_attribute' => 75,
            'interceptions' => 70,
            'attack_position' => 75,
            'vision' => 80,
            'ball_control' => 65,
            'crossing' => 60,
            'dribbling' => 65,
            'finishing' => 70,
            'free_kick_accuracy' => 75,
            'heading_accuracy' => 70,
            'long_pass' => 75,
            'short_pass' => 80,
            'marking' => 75,
            'shot_power' => 80,
            'long_shots' => 70,
            'stand_tackle' => 65,
            'slide_tackle' => 75,
            'volleys' => 75,
            'curve' => 70,
            'penalties' => 70,
            'gk_diving' => 80,
            'gk_handling' => 70,
            'gk_kicking' => 65,
            'gk_reflexes' => 80,
            'gk_positioning' => 75,
        ], $generatedAttributes);
    }

    /** @test */
    public function itCanFilterQueryByPlayerAttribute(): void
    {
        $player1 = Player::factory()->create(['player_name' => 'Player1']);
        $player2 = Player::factory()->create(['player_name' => 'Player2']);

        PlayerAttribute::factory()->create([
            'player_id' => $player1->id,
            'favourite_position' => 'G',
            'acceleration' => 80,
            'aggression' => 75,
        ]);

        PlayerAttribute::factory()->create([
            'player_id' => $player2->id,
            'favourite_position' => 'D',
            'acceleration' => 90,
            'aggression' => 85,
        ]);

        $query = PlayerAttribute::query();

        request()->merge(['acceleration' => 85, 'aggression' => 80]);

        $query->filter();

        $this->assertCount(1, $query->get());
        $this->assertEquals('Player2', $query->first()->player->player_name);
    }

    /** @test */
    public function itCanDefineARelationshipWithPlayer(): void
    {
        $player = Player::factory()->create();

        $playerAttribute = PlayerAttribute::factory()->create([
            'player_id' => $player->id,
        ]);

        $this->assertEquals($player->id, $playerAttribute->player->id);
    }

    /** @test */
    public function testPlayerRelationship(): void
    {
        $player = Player::factory()->create();
        $playerAttribute = PlayerAttribute::factory()->create(['player_id' => $player->id]);

        $relatedPlayer = $playerAttribute->player;

        $this->assertInstanceOf(Player::class, $relatedPlayer);
        $this->assertEquals($player->id, $relatedPlayer->id);
    }

    /**
     * @test
     */
    public function generateFavouritePosition(): void
    {
        // Test with a valid position string
        $position = 'Goalkeeper';
        $favouritePosition = PlayerAttribute::generateFavouritePosition($position);
        $this->assertEquals('G', $favouritePosition);

        // Test with an empty position string
        $position = '';
        $favouritePosition = PlayerAttribute::generateFavouritePosition($position);
        $this->assertNull($favouritePosition);
    }

    /**
     * @test
     */
    public function generateFavouritePositionWithInvalidPosition(): void
    {
        // Test with an invalid position string
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid position');

        $position = 'InvalidPosition';
        PlayerAttribute::generateFavouritePosition($position);
    }

    /** @test */
    public function testParseAttributes(): void
    {
        // Create sample data
        $attributeString = '85|75';

        // Make the parseAttributes method accessible
        $reflection = new ReflectionClass(PlayerAttribute::class);
        $method = $reflection->getMethod('parseAttributes');
        $method->setAccessible(true);

        // Create a new instance of the PlayerAttribute class
        $playerAttribute = new PlayerAttribute;

        // Invoke the parseAttributes method with the sample data
        $parsedAttributes = $method->invokeArgs($playerAttribute, [$attributeString]);

        // Assert that the parsed attributes are correct
        $expectedParsedAttributes = collect([85, 75]);

        $this->assertEquals($expectedParsedAttributes, $parsedAttributes);
    }

    /** @test */
    public function testGetMappedAttributes(): void
    {
        // Create sample data
        $attributeNames = ['Sprint Speed', 'Stamina', 'GK Diving'];
        $attributes = [85, 75, 70];

        // Make the getMappedAttributes method accessible
        $reflection = new ReflectionClass(PlayerAttribute::class);
        $method = $reflection->getMethod('getMappedAttributes');
        $method->setAccessible(true);

        // Create a new instance of the PlayerAttribute class
        $playerAttribute = new PlayerAttribute;

        // Invoke the getMappedAttributes method with the sample data
        $mappedAttributes = $method->invokeArgs($playerAttribute, [$attributeNames, $attributes]);

        // Assert that the mapped attributes are correct
        $expectedMappedAttributes = [
            'sprint_speed' => 85,
            'stamina' => 75,
            'gk_diving' => 70,
        ];

        $this->assertEquals($expectedMappedAttributes, $mappedAttributes);
    }

    /** @test */
    public function testScopeFilter(): void
    {
        $player1 = Player::factory()->create();
        $player2 = Player::factory()->create();
        $player3 = Player::factory()->create();

        $playerAttribute1 = PlayerAttribute::factory()->create([
            'player_id' => $player1->id,
            'favourite_position' => 'D',
            'sprint_speed' => 90,
            'stamina' => 85,
        ]);

        $playerAttribute2 = PlayerAttribute::factory()->create([
            'player_id' => $player2->id,
            'favourite_position' => 'G',
            'sprint_speed' => 95,
            'stamina' => 72,
        ]);

        $playerAttribute3 = PlayerAttribute::factory()->create([
            'player_id' => $player3->id,
            'favourite_position' => 'G',
            'sprint_speed' => 99,
            'stamina' => 99,
        ]);

        // Set the request parameters
        $requestParameters = ['sprint_speed' => 80, 'stamina' => 70];
        $this->app['request']->merge($requestParameters);

        // Use the scopeFilter method
        $filteredPlayerAttributes = PlayerAttribute::query()->filter()->get();

        // Assert that the filtered results are correct
        $this->assertCount(3, $filteredPlayerAttributes);
        $this->assertTrue($filteredPlayerAttributes->contains('id', $playerAttribute1->id));
        $this->assertTrue($filteredPlayerAttributes->contains('id', $playerAttribute2->id));
        $this->assertTrue($filteredPlayerAttributes->contains('id', $playerAttribute3->id));

        // Update the request parameters
        $requestParameters = ['sprint_speed' => 99, 'stamina' => 95];
        $this->app['request']->merge($requestParameters);

        // Use the scopeFilter method
        $filteredPlayerAttributes = PlayerAttribute::query()->filter()->get();

        // Assert that the filtered results are correct
        $this->assertCount(1, $filteredPlayerAttributes);
        $this->assertFalse($filteredPlayerAttributes->contains('id', $playerAttribute1->id));
        $this->assertFalse($filteredPlayerAttributes->contains('id', $playerAttribute2->id));
        $this->assertTrue($filteredPlayerAttributes->contains('id', $playerAttribute3->id));
    }

    /** @test */
    public function itCannotFilterQueryByPlayerAttributeWithInvalidParameters(): void
    {
        $player1 = Player::factory()->create(['player_name' => 'Player1']);
        $player2 = Player::factory()->create(['player_name' => 'Player2']);

        PlayerAttribute::factory()->create([
            'player_id' => $player1->id,
            'favourite_position' => 'G',
            'acceleration' => 80,
            'aggression' => 75,
        ]);

        PlayerAttribute::factory()->create([
            'player_id' => $player2->id,
            'favourite_position' => 'D',
            'acceleration' => 90,
            'aggression' => 85,
        ]);

        $query = PlayerAttribute::query();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Attribute must be an integerish value');

        request()->merge(['acceleration' => 'invalid', 'aggression' => 80]);

        $query->filter();
    }

    /** @test */
    public function testGetMappedAttributesWithMismatchedArrays(): void
    {
        // Create sample data with different lengths for attributeNames and attributes
        $attributeNames = ['Sprint Speed', 'Stamina', 'GK Diving'];
        $attributes = [85, 75];

        // Make the getMappedAttributes method accessible
        $reflection = new ReflectionClass(PlayerAttribute::class);
        $method = $reflection->getMethod('getMappedAttributes');
        $method->setAccessible(true);

        // Create a new instance of the PlayerAttribute class
        $playerAttribute = new PlayerAttribute;

        // Invoke the getMappedAttributes method with the sample data and catch the exception
        try {
            $method->invokeArgs($playerAttribute, [$attributeNames, $attributes]);
        } catch (Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals('Attribute names and attributes must be the same length', $e->getMessage());
        }
    }
}
