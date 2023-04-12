<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiClubTest extends TestCase
{
    use RefreshDatabase;

    public string $baseUri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->baseUri = 'club/' . $this->clubId . '/platform/' . $this->platform;
        $this->apiVersion = 'api/v1/';
    }

    /**
     * @test
     */
    public function api_club_request_returns_successfully(): void
    {
        $uri = $this->apiVersion . $this->baseUri;

        $response = $this->actingAs($this->user)->get($uri);
        $json = $response->getContent();

        $response->assertOk();
        $this->assertStringContainsString('name', $json);
        $this->assertStringContainsString('teamId', $json);
    }

    /**
     * @test
     */
    public function api_club_career_request_returns_successfully(): void
    {
        $uri = $this->apiVersion . $this->baseUri . '/career';

        $response = $this->actingAs($this->user)->get($uri);
        $json = $response->getContent();

        $response->assertOk();
        $this->assertStringContainsString('members', $json);
        $this->assertStringContainsString('positionCount', $json);
        $response->assertJsonCount(9, 'members');
        $response->assertJsonCount(4, 'positionCount');
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

        $json = $response->getContent();
        $response->assertOk();

        $this->assertStringContainsString('matchId', $json);
        $this->assertStringContainsString('clubs', $json);
        $this->assertStringContainsString('players', $json);
        $response->assertJsonStructure([
            [
                'matchId',
                'timestamp',
                'timeAgo' => [
                    'number',
                    'unit',
                ],
                'clubs' => [
                    '52003' => [
                        'gameNumber',
                        'goals',
                        'goalsAgainst',
                        'losses',
                        'result',
                        'score',
                        'season_id',
                        'TEAM',
                        'ties',
                        'winnerByDnf',
                        'wins',
                        'details' => [
                            'name',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function api_club_leaderboard_request_returns_successfully(): void
    {
        $uri = 'platform/ps5/leaderboard/club';

        $response = $this->actingAs($this->user)->get($uri);

        $json = $response->getContent();
        $response->assertOk();

        $response->assertJsonStructure([
            [
                'rank',
                'seasons',
                'titlesWon',
                'leaguesWon',
                'divsWon1',
                'divsWon2',
                'divsWon3',
                'cupsWon6',
                'cupsWon5',
                'cupsWon4',
                'cupsWon3',
                'cupsWon2',
                'cupsWon1',
                'cupsWon0',
                'totalCupsWon',
                'cupsEntered',
                'cupWinPercent',
                'promotions',
                'relegations',
                'wins',
                'ties',
                'losses',
                'maxDivision',
                'goals',
                'goalsAgainst',
                'averageGoalsPerGame',
                'averageGoalsAgainstPerGame',
                'cleanSheets',
                'starLevel',
                'agreggateRecord',
                'clubName',
                'clubId',
                'currentDivision',
                'rankingPoints',
                'clubInfo' => [
                    'name',
                    'clubId',
                    'regionId',
                    'teamId',
                    'customKit' => [],
                ],
                'platform',
            ],
        ]);
    }

    /**
     * @test
     */
    public function api_club_members_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/members';

        $response = $this->actingAs($this->user)->get($uri);

        $json = $response->getContent();
        $response->assertOk();

        $this->assertStringContainsString('members', $json);
        $this->assertStringContainsString('gamesPlayed', $json);
        $this->assertStringContainsString('assists', $json);
        $response->assertJsonStructure([
            'members' => [],
            'positionCount' => [
                'midfielder' => [],
                'goalkeeper' => [],
                'forward' => [],
                'defender' => [],
            ],
        ]);
    }

    /**
     * @test
     */
    public function api_club_player_request_returns_succesfully(): void
    {
        $player = 'zabius-uk';
        $uri = $this->baseUri . '/players/' . $player;

        $response = $this->actingAs($this->user)->get($uri);

        $json = $response->getContent();
        $response->assertOk();

        $this->assertStringContainsString('members', $json);
        $this->assertStringContainsString('gamesPlayed', $json);
        $this->assertStringContainsString('assists', $json);
        $response->assertJsonStructure([
            'career' => [
                'name',
                'proPos',
                'gamesPlayed',
                'goals',
                'assists',
                'manOfTheMatch',
                'ratingAve',
                'favoritePosition',
            ],
            'members' => [
                'name',
                'gamesPlayed',
                'winRate',
                'goals',
                'assists',
                'cleanSheetsDef',
                'cleanSheetsGK',
                'shotSuccessRate',
                'passesMade',
                'passSuccessRate',
                'tacklesMade',
                'tackleSuccessRate',
                'proName',
                'proPos',
                'proPos',
                'proHeight',
                'proNationality',
                'proOverall',
                'manOfTheMatch',
                'redCards',
                'prevGoals',
                'favoritePosition',
            ],
        ]);
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
        $response->assertJsonStructure([
            '1' => [
                'divisionName',
                'divisionId',
                'pointsForPromotion',
                'pointsToHoldDivision',
                'pointsToTitle',
            ],
        ]);
    }

    /**
     * @test
     */
    public function api_club_squad_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/squad';

        $response = $this->actingAs($this->user)->get($uri);

        $json = $response->getContent();
        $response->assertOk();

        $this->assertStringContainsString('members', $json);
        $this->assertStringContainsString('gamesPlayed', $json);
        $this->assertStringContainsString('assists', $json);
        $response->assertJsonStructure([
            'members' => [],
            'positionCount' => [
                'midfielder' => [],
                'goalkeeper' => [],
                'forward' => [],
                'defender' => [],
            ],
        ]);
    }

    /**
     * @test
     */
    public function api_club_squad_compare_request_returns_successfully(): void
    {
        $player1 = 'zabius-uk';
        $player2 = 'CarlosBlackson';
        $uri = $this->apiVersion . $this->baseUri . '/squad/compare/' . $player1 . '/' . $player2;
        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
        $response->assertJsonStructure([
            'player1' => [
                'career' => [],
                'members' => [],
            ],
            'player2' => [
                'career' => [],
                'members' => [],
            ],
        ]);
        $response->assertJsonCount(2, 'player1');
        $response->assertJsonCount(2, 'player2');
    }

    /**
     * @TODO - needs modifying to work with the new charts stuff as we now hit the database
     *
     * @test
     */
//    public function api_club_squad_request_with_non_existing_players_returns_empty_data(): void
//    {
//        $player1 = 'a-fakeplayer-999';
//        $player2 = 'another-fakeplayer-999';
//        $uri = $this->baseUri . '/squad/compare/' . $player1 . '/' . $player2;
//
//        $response = $this->actingAs($this->user)->get($uri);
//
//        $response->assertJsonFragment([
//            'player1' => [
//                'career' => null,
//                'members' => null,
//            ],
//            'player2' => [
//                'career' => null,
//                'members' => null,
//            ],
//        ]);
//        $response->assertJsonCount(2, 'player1');
//        $response->assertJsonCount(2, 'player2');
//    }

    /**
     * @test
     */
    public function api_club_seasonal_request_returns_successfully(): void
    {
        $uri = $this->baseUri . '/season';

        $response = $this->actingAs($this->user)->get($uri);

        $json = $response->getContent();
        $response->assertOk();

        $this->assertStringContainsString('clubId', $json);
        $this->assertStringContainsString('seasons', $json);
        $this->assertStringContainsString('leaguesWon', $json);
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
