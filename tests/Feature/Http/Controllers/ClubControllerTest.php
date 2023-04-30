<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClubControllerTest extends TestCase
{
    use RefreshDatabase;

    public string $baseUri;
    public int $clubId;
    public string $platform;

    protected function setUp(): void
    {
        parent::setUp();

        $this->baseUri = 'club/' . $this->clubId . '/platform/' . $this->platform;
    }

    /**
     * @test
     */
    public function apiClubRequestReturnsSuccessfullyWithValidJsonStructure(): void
    {
        $uri = $this->baseUri;

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
        $response->assertJsonCount(5, '52003');
        $response->assertJsonCount(17, '52003.customKit');
        $response->assertJsonStructure([
            '52003' => [
                'name',
                'clubId',
                'regionId',
                'teamId',
                'customKit' => [
                    'stadName',
                    'kitId',
                    'isCustomTeam',
                    'customKitId',
                    'customAwayKitId',
                    'customKeeperKitId',
                    'kitColor1',
                    'kitColor2',
                    'kitColor3',
                    'kitColor4',
                    'kitAColor1',
                    'kitAColor2',
                    'kitAColor3',
                    'kitAColor4',
                    'dCustomKit',
                    'crestColor',
                    'crestAssetId',
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function apiClubCareerRequestReturnsSuccessfullyWithValidJsonStructure(): void
    {
        $uri = $this->baseUri . '/career';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
        $response->assertJsonCount(4, 'positionCount');
        $response->assertJsonCount(8, 'members.0');
        $response->assertJsonStructure([
            'members' => [
                '*' => [
                    'name',
                    'proPos',
                    'gamesPlayed',
                    'goals',
                    'assists',
                    'manOfTheMatch',
                    'ratingAve',
                    'favoritePosition',
                ],
            ],
            'positionCount' => [
                'midfielder',
                'goalkeeper',
                'forward',
                'defender',
            ],
        ]);
    }

    /**
     * @test
     */
    public function apiClubCupRequestReturnsSuccessfully(): void
    {
        $uri = $this->baseUri . '/cup';

        $response = $this->actingAs($this->user)->get($uri);

        // NOTE - lots of clubs don't play cup games so this test doesn't really require a valid json structure like
        // the 'league' equivalent
        $response->assertOk();
    }

    /**
     * @test
     */
    public function apiClubFormRequestReturnsSuccessfully(): void
    {
        $uri = $this->baseUri . '/form';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function apiClubLeagueRequestReturnsSuccessfullyWithValidJsonStructure(): void
    {
        $uri = $this->baseUri . '/league';

        $response = $this->actingAs($this->user)->get($uri);
        $response->assertOk();
        $response->assertJsonCount(2, '0.timeAgo');
        $response->assertJsonCount(2, '0.clubs');
        $response->assertJsonCount(2, '0.players');
        $response->assertJsonCount(2, '0.aggregate');
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
                'players' => [
                    '*' => [
                        '*' => [
                            'assists',
                            'cleansheetsany',
                            'cleansheetsdef',
                            'cleansheetsgk',
                            'goals',
                            'goalsconceded',
                            'losses',
                            'mom',
                            'namespace',
                            'passattempts',
                            'passesmade',
                            'pos',
                            'rating',
                            'realtimegame',
                            'realtimeidle',
                            'redcards',
                            'saves',
                            'SCORE',
                            'shots',
                            'tackleattempts',
                            'tacklesmade',
                            'vproattr',
                            'vprohackreason',
                            'wins',
                            'playername',
                        ],
                    ],
                ],
                'aggregate' => [
                    '*' => [
                        'assists',
                        'cleansheetsany',
                        'cleansheetsdef',
                        'cleansheetsgk',
                        'goals',
                        'goalsconceded',
                        'losses',
                        'mom',
                        'namespace',
                        'passattempts',
                        'passesmade',
                        'pos',
                        'rating',
                        'realtimegame',
                        'realtimeidle',
                        'redcards',
                        'saves',
                        'SCORE',
                        'shots',
                        'tackleattempts',
                        'tacklesmade',
                        'vproattr',
                        'vprohackreason',
                        'wins',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function apiClubLeaderboardRequestReturnsSuccessfullyWithValidJsonStructure(): void
    {
        $uri = 'platform/ps5/leaderboard/club';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
        $response->assertJsonCount(98, '*');
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
                    'customKit' => [
                        'stadName',
                        'kitId',
                        'isCustomTeam',
                        'customKitId',
                        'customAwayKitId',
                        'customKeeperKitId',
                        'kitColor1',
                        'kitColor2',
                        'kitColor3',
                        'kitColor4',
                        'kitAColor1',
                        'kitAColor2',
                        'kitAColor3',
                        'kitAColor4',
                        'dCustomKit',
                        'crestColor',
                        'crestAssetId',
                    ],
                ],
                'platform',
            ],
        ]);
    }

    /**
     * @test
     */
    public function apiClubMembersRequestReturnsSuccessfullyWithValidJsonStructure(): void
    {
        $uri = $this->baseUri . '/members';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
        $response->assertJsonCount(4, 'positionCount');
        $response->assertJsonStructure([
            'members' => [
                '*' => [
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
                    'proStyle',
                    'proHeight',
                    'proNationality',
                    'proOverall',
                    'manOfTheMatch',
                    'redCards',
                    'prevGoals',
                    'favoritePosition',
                ],
            ],
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
    public function apiClubPlayerRequestReturnsSuccesfullyWithValidJsonStructure(): void
    {
        $player = 'zabius-uk';
        $uri = $this->baseUri . '/players/' . $player;

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
        $response->assertJsonCount(8, 'career');
        $response->assertJsonCount(22, 'members');
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
    public function apiSearchRequestReturnsSuccessfully(): void
    {
        $uri = $this->baseUri . '/search';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function apiSettingsRequestReturnsSuccessfullyWithValidJsonStructure(): void
    {
        $uri = $this->baseUri . '/settings';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
        $response->assertJsonCount(10, '*');
        $response->assertJsonStructure([
            '*' => [
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
    public function apiClubSquadRequestReturnsSuccessfullyWithValidJsonStructure(): void
    {
        $uri = $this->baseUri . '/squad';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
        $response->assertJsonCount(22, 'members.0');
        $response->assertJsonCount(4, 'positionCount');
        $response->assertJsonStructure([
            'members' => [
                '*' => [
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
                    'proStyle',
                    'proHeight',
                    'proNationality',
                    'proOverall',
                    'manOfTheMatch',
                    'redCards',
                    'prevGoals',
                    'favoritePosition',
                ],
            ],
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
    public function apiClubSeasonalRequestReturnsSuccessfullyWithValidJsonStructure(): void
    {
        $uri = $this->baseUri . '/season';

        $response = $this->actingAs($this->user)->get($uri);

        $response->assertOk();
        $response->assertJsonCount(1, '*');
        $response->assertJsonCount(105, '*.*');
        $response->assertJsonStructure([
            '*' => [
                'clubId',
                'leaguesWon',
                'divsWon1',
                'divsWon2',
                'divsWon3',
                'divsWon4',
                'cupsWon0',
                'cupsWon1',
                'cupsWon2',
                'cupsWon3',
                'cupsWon4',
                'cupsWon5',
                'cupsWon6',
                'cupsElim0',
                'cupsElim0R1',
                'cupsElim0R2',
                'cupsElim0R3',
                'cupsElim0R4',
                'cupsElim1',
                'cupsElim1R1',
                'cupsElim1R2',
                'cupsElim1R3',
                'cupsElim1R4',
                'cupsElim2',
                'cupsElim2R1',
                'cupsElim2R2',
                'cupsElim2R3',
                'cupsElim2R4',
                'cupsElim3',
                'cupsElim3R1',
                'cupsElim3R2',
                'cupsElim3R3',
                'cupsElim3R4',
                'cupsElim4',
                'cupsElim4R1',
                'cupsElim4R2',
                'cupsElim4R3',
                'cupsElim4R4',
                'cupsElim5',
                'cupsElim5R1',
                'cupsElim5R2',
                'cupsElim5R3',
                'cupsElim5R4',
                'cupsElim6',
                'cupsElim6R1',
                'cupsElim6R2',
                'cupsElim6R3',
                'cupsElim6R4',
                'promotions',
                'holds',
                'relegations',
                'rankingPoints',
                'prevDivision',
                'maxDivision',
                'bestDivision',
                'bestPoints',
                'curSeasonMov',
                'lastMatch0',
                'lastMatch1',
                'lastMatch2',
                'lastMatch3',
                'lastMatch4',
                'lastMatch5',
                'lastMatch6',
                'lastMatch7',
                'lastMatch8',
                'lastMatch9',
                'lastOpponent0',
                'lastOpponent1',
                'lastOpponent2',
                'lastOpponent3',
                'lastOpponent4',
                'lastOpponent5',
                'lastOpponent6',
                'lastOpponent7',
                'lastOpponent8',
                'lastOpponent9',
                'starLevel',
                'cupRankingPoints',
                'overallRankingPoints',
                'alltimeGoals',
                'alltimeGoalsAgainst',
                'seasonWins',
                'seasonTies',
                'seasonLosses',
                'gamesPlayed',
                'goals',
                'goalsAgainst',
                'points',
                'prevSeasonWins',
                'prevSeasonTies',
                'prevSeasonLosses',
                'prevPoints',
                'prevProjectedPts',
                'skill',
                'wins',
                'ties',
                'losses',
                'currentDivision',
                'projectedPoints',
                'totalCupsWon',
                'recentResults',
                'totalGames',
            ],
        ]);
    }
}
