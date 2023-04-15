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

    public function publicFilterPlayerData(object $players, string $matchedPlayer)
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

    private $apiServiceMock;
    private $resultService;

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
    public function getPlayerComparisonData()
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
    public function getCachedData()
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
    public function getRankingData()
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
    public function getCustomRankingData()
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
    public function getSquadData()
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
    public function getCareerData()
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
    public function filterPlayerData()
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
    public function sortingCustomRankingData()
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
    public function getRankingTypes()
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
}
