<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClubTest extends TestCase
{
    use RefreshDatabase;

    public string $baseUri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->baseUri = 'club/' . $this->clubId . '/platform/' . $this->platform;
    }

    /**
     * @test
     */
    public function api_club_request_returns_successfully(): void
    {
        $uri = $this->baseUri;

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function api_club_career_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/career';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function api_club_cup_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/cup';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function api_club_form_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/form';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function api_club_league_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/league';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function api_club_leaderboard_request_returns_successfully(): void
    {
        $uri = 'platform/ps5/leaderboard/club';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function api_club_members_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/members';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function api_club_player_request_returns_succesfully(): void
    {
        $player = 'zabius-uk';
        $uri = $this->baseUri . '/players/' . $player;

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function api_search_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/search';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function api_settings_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/settings';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function api_club_squad_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/squad';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function api_club_squad_compare_request_returns_successfully(): void
    {
        $player1 = 'zabius-uk';
        $player2 = 'CarlosBlackson';
        $uri = $this->baseUri . '/squad/compare/' . $player1 . '/' . $player2;

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
        $response->assertJsonCount(2, 'player1');
        $response->assertJsonCount(2, 'player2');
    }

    /**
     * @test
     */
    public function api_club_squad_request_with_non_existing_players_returns_empty_data(): void
    {
        $player1 = 'a-fakeplayer-999';
        $player2 = 'another-fakeplayer-999';
        $uri = $this->baseUri . '/squad/compare/' . $player1 . '/' . $player2;

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertJsonFragment([
            'player1' => [
                'career' => null,
                'members' => null,
            ],
            'player2' => [
                'career' => null,
                'members' => null,
            ],
        ]);
        $response->assertJsonCount(2, 'player1');
        $response->assertJsonCount(2, 'player2');
    }

    /**
     * @test
     */
    public function api_club_seasonal_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/season';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function api_club_members_request_returns_expected_data(): void
    {
        $uri = $this->baseUri . '/members';

        $response = $this->actingAs($this->user)->get($uri);
        $json = $response->getContent();

        $response->assertOk();

        $this->assertIsString($json);
        $this->assertStringContainsString('assists', $json);
        $this->assertStringContainsString('favoritePosition', $json);
        $this->assertStringContainsString('gamesPlayed', $json);
        $this->assertStringContainsString('goals', $json);
        $this->assertStringContainsString('manOfTheMatch', $json);
        $this->assertStringContainsString('members', $json);
        $this->assertStringContainsString('name', $json);
        $this->assertStringContainsString('winRate', $json);
    }
}
