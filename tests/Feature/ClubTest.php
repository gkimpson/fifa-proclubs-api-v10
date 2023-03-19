<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClubTest extends TestCase
{
    use RefreshDatabase;

    public string $baseUri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->baseUri = 'club/'. $this->clubId .'/platform/'. $this->platform;
    }

    /**
     * @test
     */
    public function test_api_club_request_returns_successfully(): void
    {
        $uri = $this->baseUri;

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    public function test_api_club_career_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/career';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    public function test_api_club_cup_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/cup';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    public function test_api_club_form_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/form';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    public function test_api_club_league_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/league';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    public function test_api_club_leaderboard_request_returns_successfully(): void
    {
        $uri = 'platform/ps5/leaderboard/club';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    public function test_api_club_members_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/members';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    public function test_api_club_player_request_returns_succesfully(): void
    {
        $player = 'zabius-uk';
        $uri = $this->baseUri . '/players/'. $player;

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    public function test_api_search_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/search';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    public function test_api_settings_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/settings';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    public function test_api_club_squad_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/squad';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    public function test_api_club_squad_ranking_request_returns_successfully(): void
    {
        $player1 = 'zabius-uk';
        $player2 = 'CarlosBlackson';
        $uri = $this->baseUri . '/squad/compare/'. $player1 . '/'. $player2;

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

}
