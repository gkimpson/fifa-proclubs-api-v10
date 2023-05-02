<?php

namespace Tests\Unit\Services;

use App\Services\ProclubsApiService;
use App\Services\ResultService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class TestableResultService extends ResultService
{
    public function __construct(ProclubsApiService $apiService)
    {
        parent::__construct($apiService);
    }

    public function getCachedDataMock(int $clubId, string $platform, string $cacheName): object
    {
        return parent::getCachedData($clubId, $platform, $cacheName);
    }

    public function mapRankingDataMock(object $members, string $sortingMethod): array
    {
        return parent::mapRankingData($members, $sortingMethod);
    }

    public function publicGetSquadData(int $clubId, string $platform): object
    {
        return $this->getSquadData($clubId, $platform);
    }

    public function publicGetCareerData(int $clubId, string $platform): object
    {
        return $this->getCareerData($clubId, $platform);
    }

    public function publicFilterPlayerData(object $players, string $matchedPlayer): object|null
    {
        return $this->filterPlayerData($players, $matchedPlayer);
    }

    public function publicSortingCustomRankingData(string $rankingType, object $membersObject): array
    {
        return $this->sortingCustomRankingData($rankingType, $membersObject);
    }
}

class ResultServiceTest extends TestCase
{
    use RefreshDatabase;

    private object $apiServiceMock;
    private object $resultService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiServiceMock = Mockery::mock(ProclubsApiService::class);
        $this->resultService = new ResultService($this->apiServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getPlayerComparisonData(): void
    {
        $clubId = 1;
        $platform = 'ps5';
        $player1 = 'player1';
        $player2 = 'player2';

        $this->apiServiceMock->shouldReceive('memberStats')
            ->andReturn(json_encode(['members' => [
                ['name' => $player1, 'goals' => 10],
                ['name' => $player2, 'goals' => 15],
            ]]));

        $this->apiServiceMock->shouldReceive('careerStats')
            ->andReturn(json_encode(['members' => [
                ['name' => $player1, 'goals' => 50],
                ['name' => $player2, 'goals' => 60],
            ]]));

        $result = $this->resultService->getPlayerComparisonData($clubId, $platform, $player1, $player2);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);

        $this->assertEquals([
            'player1' => [
                'career' => (object) ['name' => $player1, 'goals' => 50],
                'members' => (object) ['name' => $player1, 'goals' => 10],
            ],
            'player2' => [
                'career' => (object) ['name' => $player2, 'goals' => 60],
                'members' => (object) ['name' => $player2, 'goals' => 15],
            ],
        ], $result);
    }

    /**
     * @test
     */
    public function getCachedData(): void
    {
        $clubId = 1;
        $platform = 'ps5';
        $cacheName = 'squad';

        $this->apiServiceMock->shouldReceive('memberStats')
            ->andReturn(json_encode(['members' => [
                ['name' => 'player1', 'goals' => 10],
                ['name' => 'player2', 'goals' => 15],
            ]]));

        $expectedResult = json_decode('{"members":[{"name":"player1","goals":10},{"name":"player2","goals":15}]}');

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn($expectedResult);

        $result = $this->resultService->getCachedData($clubId, $platform, $cacheName);

        $this->assertIsObject($result);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @test
     */
    public function getRankingData(): void
    {
        $clubId = 1;
        $platform = 'ps5';

        $this->apiServiceMock->shouldReceive('memberStats')
            ->andReturn(json_encode(['members' => [
                ['name' => 'player1', 'goals' => 10],
                ['name' => 'player2', 'goals' => 15],
            ]]));

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $result = $this->resultService->getRankingData($clubId, $platform);

        // Perform assertions on $result as needed
        $this->assertIsArray($result);
        $this->assertCount(17, $result);
        $this->assertArrayHasKey('goals', $result);
        $this->assertArrayHasKey('player1', $result['goals']);
        $this->assertArrayHasKey('player2', $result['goals']);
        $this->assertEquals(10, $result['goals']['player1']);
        $this->assertEquals(15, $result['goals']['player2']);
    }

    /**
     * @test
     */
    public function getCustomRankingData(): void
    {
        $clubId = 1;
        $platform = 'ps5';

        $this->apiServiceMock->shouldReceive('memberStats')
            ->andReturn(json_encode(['members' => [
                ['name' => 'player1', 'goals' => 5, 'gamesPlayed' => 5],
                ['name' => 'player2', 'goals' => 30, 'gamesPlayed' => 10],
            ]]));

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $result = $this->resultService->getCustomRankingData($clubId, $platform);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('gamesPlayed', $result);
        $this->assertArrayHasKey('player1', $result['gamesPlayed']);
        $this->assertArrayHasKey('player2', $result['gamesPlayed']);
        $this->assertEquals(1, $result['goals']['player1']);
        $this->assertEquals(3, $result['goals']['player2']);
    }

    /**
     * @test
     */
    public function getSquadData(): void
    {
        $clubId = 1;
        $platform = 'ps5';

        $this->apiServiceMock->shouldReceive('memberStats')
            ->andReturn(json_encode([
                'members' => [
                    ['name' => 'player1', 'goals' => 10],
                    ['name' => 'player2', 'goals' => 15],
                ],
            ]));

        $testableResultService = new TestableResultService($this->apiServiceMock);
        $result = $testableResultService->publicGetSquadData($clubId, $platform);

        $this->assertIsObject($result);
        $members = collect($result->members);
        $this->assertCount(2, $members);

        $player1 = $members->firstWhere('name', 'player1');
        $player2 = $members->firstWhere('name', 'player2');

        $this->assertEquals('player1', $player1->name);
        $this->assertEquals(10, $player1->goals);

        $this->assertEquals('player2', $player2->name);
        $this->assertEquals(15, $player2->goals);
    }

    /**
     * @test
     */
    public function getCareerData(): void
    {
        $clubId = 1;
        $platform = 'ps5';

        $this->apiServiceMock->shouldReceive('careerStats')
            ->andReturn(json_encode([
                'members' => [
                    ['name' => 'player1', 'goals' => 50],
                    ['name' => 'player2', 'goals' => 60],
                ],
            ]));

        $testableResultService = new TestableResultService($this->apiServiceMock);
        $result = $testableResultService->publicGetCareerData($clubId, $platform);

        $this->assertIsObject($result);

        $members = collect($result->members);
        $this->assertCount(2, $members);

        $player1 = $members->firstWhere('name', 'player1');
        $player2 = $members->firstWhere('name', 'player2');

        $this->assertEquals('player1', $player1->name);
        $this->assertEquals(50, $player1->goals);

        $this->assertEquals('player2', $player2->name);
        $this->assertEquals(60, $player2->goals);
    }

    /**
     * @test
     */
    public function filterPlayerData(): void
    {
        $playersJson = json_encode([
            'members' => [
                ['name' => 'player1', 'goals' => 10],
                ['name' => 'player2', 'goals' => 15],
                ['name' => 'player3', 'goals' => 5],
            ],
        ]);

        $players = json_decode($playersJson);
        $matchedPlayer = 'player2';

        $testableResultService = new TestableResultService($this->apiServiceMock);
        $result = $testableResultService->publicFilterPlayerData($players, $matchedPlayer);

        $this->assertNotNull($result);
        $this->assertEquals('player2', $result->name);
        $this->assertEquals(15, $result->goals);

        $matchedPlayer = 'nonexistent';
        $result = $testableResultService->publicFilterPlayerData($players, $matchedPlayer);

        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function sortingCustomRankingData(): void
    {
        $membersJson = json_encode([
            'members' => [
                ['name' => 'player1', 'goals' => 10, 'gamesPlayed' => 5],
                ['name' => 'player2', 'goals' => 15, 'gamesPlayed' => 10],
                ['name' => 'player3', 'goals' => 0, 'gamesPlayed' => 3],
            ],
        ]);

        $membersObject = json_decode($membersJson);
        $rankingType = 'goals';

        $testableResultService = new TestableResultService($this->apiServiceMock);
        $result = $testableResultService->publicSortingCustomRankingData($rankingType, $membersObject);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals(['player2' => 1.5, 'player1' => 2], $result);

        // Test with a nonexistent rankingType
        $rankingType = 'nonexistent';
        $result = $testableResultService->publicSortingCustomRankingData($rankingType, $membersObject);

        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function getRankingTypes(): void
    {
        $resultService = new ResultService(new ProclubsApiService);

        $expectedRankingTypes = [
            'assists',
            'cleanSheetsDef',
            'cleanSheetsGk',
            'favoritePosition',
            'gamesPlayed',
            'goals',
            'manOfTheMatch',
            'passSuccessRate',
            'passesMade',
            'prevGoals',
            'proHeight',
            'proOverall',
            'ratingAve',
            'shotSuccessRate',
            'tackleSuccessRate',
            'tacklesMade',
            'winRate',
        ];

        $this->assertEquals($expectedRankingTypes, $resultService->getRankingTypes());
    }

    /**
     * @test
     */
    public function insertMatches()
    {
        $results = $this->getValidResultsData();
        $platform = 'ps5';

        $inserted = ResultService::insertMatches($results, $platform);

        $this->assertEquals(1, $inserted);
        $this->assertDatabaseCount('results', 1);
        $this->assertDatabaseHas('results', [
            'match_id' => '80584593790065',
            'home_team_id' => 52003,
            'away_team_id' => 9815558,
            'home_team_goals' => 0,
            'away_team_goals' => 2,
            'home_team_player_count' => 2,
            'away_team_player_count' => 2,
            'outcome' => 'awaywin',
            'platform' => 'ps5',
        ]);
    }

    public function getValidResultsData(): array
    {
        $results[] = [
            'matchId' => '80584593790065',
            'timestamp' => 1682881393,
            'clubs' => [
                0 => [
                    'id' => 52003,
                    'name' => 'Banterbury',
                    'goals' => '0',
                    'goalsAgainst' => '2',
                    'seasonId' => null,
                    'winnerByDnf' => '0',
                    'wins' => '0',
                    'losses' => '1',
                    'ties' => '0',
                    'gameNumber' => '3',
                    'result' => '2',
                    'teamId' => 112172,
                ],
                1 => [
                    'id' => 9815558,
                    'name' => 'The Kickerz',
                    'goals' => '2',
                    'goalsAgainst' => '0',
                    'seasonId' => null,
                    'winnerByDnf' => '0',
                    'wins' => '1',
                    'losses' => '0',
                    'ties' => '0',
                    'gameNumber' => '6',
                    'result' => '1',
                    'teamId' => 73,
                ],
            ],
            'players' => [
                52003 => [
                    194826847 => [
                        'SCORE' => '0',
                        'assists' => '0',
                        'cleansheetsany' => '0',
                        'cleansheetsdef' => '0',
                        'cleansheetsgk' => '0',
                        'goals' => '0',
                        'goalsconceded' => '2',
                        'losses' => '1',
                        'mom' => '0',
                        'namespace' => '1',
                        'passattempts' => '21',
                        'passesmade' => '13',
                        'playername' => 'CarlosBlackson',
                        'pos' => 'forward',
                        'rating' => '7.40',
                        'realtimegame' => '925',
                        'realtimeidle' => '24',
                        'redcards' => '0',
                        'saves' => '0',
                        'shots' => '3',
                        'tackleattempts' => '5',
                        'tacklesmade' => '0',
                        'vproattr' => '084|089|080|084|086|093|089|099|083|089|060|099|075|087|074|082|096|078|090|069|077|054|088|084|055|048|096|085|083|010|010|010|010|010|',
                        'vprohackreason' => '8',
                        'wins' => '0',
                    ],
                    220480885 => [
                        'SCORE' => '0',
                        'assists' => '0',
                        'cleansheetsany' => '0',
                        'cleansheetsdef' => '0',
                        'cleansheetsgk' => '0',
                        'goals' => '0',
                        'goalsconceded' => '2',
                        'losses' => '1',
                        'mom' => '0',
                        'namespace' => '1',
                        'passattempts' => '21',
                        'passesmade' => '13',
                        'playername' => 'CarlosBlackson',
                        'pos' => 'forward',
                        'rating' => '7.40',
                        'realtimegame' => '925',
                        'realtimeidle' => '24',
                        'redcards' => '0',
                        'saves' => '0',
                        'shots' => '3',
                        'tackleattempts' => '5',
                        'tacklesmade' => '0',
                        'vproattr' => '084|089|080|084|086|093|089|099|083|089|060|099|075|087|074|082|096|078|090|069|077|054|088|084|055|048|096|085|083|010|010|010|010|010|',
                        'vprohackreason' => '8',
                        'wins' => '0',
                    ],
                ],
                9815558 => [
                    1342911023 => [
                        'SCORE' => '2',
                        'assists' => '2',
                        'cleansheetsany' => '1',
                        'cleansheetsdef' => '0',
                        'cleansheetsgk' => '0',
                        'goals' => '1',
                        'goalsconceded' => '0',
                        'losses' => '0',
                        'mom' => '0',
                        'namespace' => '1',
                        'passattempts' => '144',
                        'passesmade' => '118',
                        'playername' => 'atabey385',
                        'pos' => '',
                        'rating' => '7.50',
                        'realtimegame' => '925',
                        'realtimeidle' => '4',
                        'redcards' => '0',
                        'saves' => '0',
                        'shots' => '5',
                        'tackleattempts' => '24',
                        'tacklesmade' => '7',
                        'vproattr' => 'NH',
                        'vprohackreason' => '0',
                        'wins' => '1',
                    ],
                    1716617416 => [
                        'SCORE' => '2',
                        'assists' => '0',
                        'cleansheetsany' => '0',
                        'cleansheetsdef' => '0',
                        'cleansheetsdef' => '0',
                        'cleansheetsgk' => '0',
                        'goals' => '1',
                        'goalsconceded' => '0',
                        'losses' => '0',
                        'mom' => '1',
                        'namespace' => '1',
                        'passattempts' => '25',
                        'passesmade' => '21',
                        'playername' => 'VullnetCR7',
                        'pos' => 'forward',
                        'rating' => '8.30',
                        'realtimegame' => '925',
                        'realtimeidle' => '7',
                        'redcards' => '0',
                        'saves' => '0',
                        'shots' => '6',
                        'tackleattempts' => '5',
                        'tacklesmade' => '1',
                        'vproattr' => '093|091|077|080|072|086|075|089|068|088|060|091|077|088|078|085|095|084|075|072|081|052|093|092|057|050|085|079|091|010|010|010|010|010|',
                        'vprohackreason' => '8',
                        'wins' => '1',
                    ],
                ],
            ],
            'aggregate' => [
                52003 => [
                    'SCORE' => '0',
                    'assists' => '0',
                    'cleansheetsany' => '0',
                    'cleansheetsdef' => '0',
                    'cleansheetsgk' => '0',
                    'goals' => '0',
                    'goalsconceded' => '2',
                    'losses' => '1',
                    'mom' => '0',
                    'namespace' => '1',
                    'passattempts' => '21',
                    'passesmade' => '13',
                    'playername' => 'CarlosBlackson',
                    'pos' => 'forward',
                    'rating' => '7.40',
                    'realtimegame' => '925',
                    'realtimeidle' => '24',
                    'redcards' => '0',
                    'saves' => '0',
                    'shots' => '3',
                    'tackleattempts' => '5',
                    'tacklesmade' => '0',
                    'vproattr' => '084|089|080|084|086|093|089|099|083|089|060|099|075|087|074|082|096|078|090|069|077|054|088|084|055|048|096|085|083|010|010|010|010|010|',
                    'vprohackreason' => '8',
                    'wins' => '0',
                ],
                9815558 => [
                    'SCORE' => '2',
                    'assists' => '2',
                    'cleansheetsany' => '1',
                    'cleansheetsdef' => '0',
                    'cleansheetsgk' => '0',
                    'goals' => '1',
                    'goalsconceded' => '0',
                    'losses' => '0',
                    'mom' => '0',
                    'namespace' => '1',
                    'passattempts' => '144',
                    'passesmade' => '118',
                    'playername' => 'atabey385',
                    'pos' => '',
                    'rating' => '7.50',
                    'realtimegame' => '925',
                    'realtimeidle' => '4',
                    'redcards' => '0',
                    'saves' => '0',
                    'shots' => '5',
                    'tackleattempts' => '24',
                    'tacklesmade' => '7',
                    'vproattr' => 'NH',
                    'vprohackreason' => '0',
                    'wins' => '1',
                ],
            ],
        ];

        return $results;
    }
}
