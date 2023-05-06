<?php

namespace Tests\Unit\Http\Controllers;

use App\Helpers\PlayerAttributesHelper;
use App\Http\Controllers\PlayerController;
use App\Models\Player;
use App\Models\PlayerAttribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerControllerTest extends TestCase
{
    use RefreshDatabase; // Rollback transactions after each test

    /**
     * @test
     */
    public function searchReturnsViewWithData()
    {
        // Seed the database with some player attributes
        $player1 = Player::factory(1)->create();
        PlayerAttribute::factory(1)->create(
            [
                'player_id' => $player1[0]->id,
                'agility' => 80,
                'balance' => 88,
                'ball_control' => 90,
                'crossing' => 77,
            ]
        );

        $player2 = Player::factory(1)->create();
        PlayerAttribute::factory(1)->create(
            [
                'player_id' => $player2[0]->id,
                'agility' => 60,
                'balance' => 65,
                'ball_control' => 70,
                'crossing' => 69,
            ]
        );

        // Call the search method of the PlayerController
        $controller = new PlayerController;
        $response = $controller->search();

        // Assert that the response is a view
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);

        // Assert that the view has an 'attributes' variable
        $this->assertTrue($response->offsetExists('attributes'));

        // Assert that the 'attributes' variable contains the correct data
        $expectedAttributes = PlayerAttributesHelper::getPlayerAttributeNames();
        $actualAttributes = $response['attributes'];
        $this->assertEquals($expectedAttributes, $actualAttributes);

        // Assert that the view has a 'players' variable
        $this->assertTrue($response->offsetExists('players'));

        // Assert that the 'players' variable contains the correct data
        $expectedPlayers = PlayerAttribute::with('player')->get()->sortBy('player.player_name')->take(10);
        $actualPlayers = $response['players']->toArray();

        // test 1st player
        $this->assertEquals($expectedPlayers[0]->acceleration, $actualPlayers[0]['acceleration']);
        $this->assertEquals(80, $actualPlayers[0]['agility']);
        $this->assertEquals(88, $actualPlayers[0]['balance']);
        $this->assertEquals(90, $actualPlayers[0]['ball_control']);
        $this->assertEquals(77, $actualPlayers[0]['crossing']);
        $this->assertEquals($expectedPlayers[0]->finishing, $actualPlayers[0]['finishing']);
        $this->assertEquals($expectedPlayers[0]->free_kick_accuracy, $actualPlayers[0]['free_kick_accuracy']);
        $this->assertEquals($expectedPlayers[0]->gk_diving, $actualPlayers[0]['gk_diving']);
        $this->assertEquals($expectedPlayers[0]->gk_handling, $actualPlayers[0]['gk_handling']);
        $this->assertEquals($expectedPlayers[0]->gk_kicking, $actualPlayers[0]['gk_kicking']);
        $this->assertEquals($expectedPlayers[0]->gk_positioning, $actualPlayers[0]['gk_positioning']);
        $this->assertEquals($expectedPlayers[0]->gk_reflexes, $actualPlayers[0]['gk_reflexes']);
        $this->assertEquals($expectedPlayers[0]->heading_accuracy, $actualPlayers[0]['heading_accuracy']);
        $this->assertEquals($expectedPlayers[0]->interceptions, $actualPlayers[0]['interceptions']);
        $this->assertEquals($expectedPlayers[0]->jumping, $actualPlayers[0]['jumping']);
        $this->assertEquals($expectedPlayers[0]->long_pass, $actualPlayers[0]['long_pass']);
        $this->assertEquals($expectedPlayers[0]->long_shots, $actualPlayers[0]['long_shots']);
        $this->assertEquals($expectedPlayers[0]->marking, $actualPlayers[0]['marking']);
        $this->assertEquals($expectedPlayers[0]->penalties, $actualPlayers[0]['penalties']);
        $this->assertEquals($expectedPlayers[0]->reactions, $actualPlayers[0]['reactions']);
        $this->assertEquals($expectedPlayers[0]->short_pass, $actualPlayers[0]['short_pass']);
        $this->assertEquals($expectedPlayers[0]->shot_power, $actualPlayers[0]['shot_power']);
        $this->assertEquals($expectedPlayers[0]->slide_tackle, $actualPlayers[0]['slide_tackle']);
        $this->assertEquals($expectedPlayers[0]->stamina, $actualPlayers[0]['stamina']);
        $this->assertEquals($expectedPlayers[0]->strength, $actualPlayers[0]['strength']);
        $this->assertEquals($expectedPlayers[0]->unsure_attribute, $actualPlayers[0]['unsure_attribute']);
        $this->assertEquals($expectedPlayers[0]->vision, $actualPlayers[0]['vision']);
        $this->assertEquals($expectedPlayers[0]->volleys, $actualPlayers[0]['volleys']);

        // test 2nd player
        $this->assertEquals(60, $expectedPlayers[1]->agility);
        $this->assertEquals(65, $expectedPlayers[1]->balance);
        $this->assertEquals(70, $expectedPlayers[1]->ball_control);
        $this->assertEquals(69, $expectedPlayers[1]->crossing);
    }

    /**
     * @test
     */
    public function searchFiltersPlayers()
    {
        // Seed the database with some player attributes
        $attributes = factory(PlayerAttribute::class, 5)->create();

        // Call the search method of the PlayerController with a filter query parameter
        $controller = new PlayerController;
        $response = $controller->search(['filter' => $attributes[0]->value]);

        // Assert that the 'players' variable contains only the filtered players
        $expectedPlayers = PlayerAttribute::where('value', $attributes[0]->value)->with('player')->get()->sortBy('player.player_name')->take(10);
        $actualPlayers = $response['players']->items();
        $this->assertEquals($expectedPlayers->pluck('id'), collect($actualPlayers)->pluck('id'));
    }
}
