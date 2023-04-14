<?php

namespace Tests\Unit\Models;

use App\Models\ResultDataFormatter;
use App\Enums\Outcomes;
use Carbon\Carbon;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use stdClass;


class ResultDataFormatterTest extends TestCase
{
    public function testGetMatchOutcome(): void
    {
        // Test with club data indicating a home win
        $clubData = ['wins' => '1', 'losses' => '0', 'ties' => '0'];
        $matchOutcome = $this->invokeGetMatchOutcome($clubData);
        $this->assertEquals(Outcomes::HOMEWIN->name(), $matchOutcome);

        // Test with club data indicating an away win
        $clubData = ['wins' => '0', 'losses' => '1', 'ties' => '0'];
        $matchOutcome = $this->invokeGetMatchOutcome($clubData);
        $this->assertEquals(Outcomes::AWAYWIN->name(), $matchOutcome);

        // Test with club data indicating a draw
        $clubData = ['wins' => '0', 'losses' => '0', 'ties' => '1'];
        $matchOutcome = $this->invokeGetMatchOutcome($clubData);
        $this->assertEquals(Outcomes::DRAW->name(), $matchOutcome);
    }

    public function testGetMatchOutcomeWithInvalidClubData(): void
    {
        // Test with invalid club data
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid club data provided.');

        $clubData = ['wins' => '0', 'losses' => '0', 'ties' => '0'];
        $this->invokeGetMatchOutcome($clubData);
    }

    private function invokeGetMatchOutcome(array $clubData): string
    {
        $reflection = new \ReflectionClass(ResultDataFormatter::class);
        $method = $reflection->getMethod('getMatchOutcome');
        $method->setAccessible(true);

        // Create a new instance of the ResultDataFormatter class
        $resultDataFormatter = new ResultDataFormatter();

        // Invoke the getMatchOutcome method with the club data
        return $method->invokeArgs($resultDataFormatter, [$clubData]);
    }

    public function testGetPlayerStats(): void
    {
        // Prepare the input player object
        $player = new stdClass();
        $player->SCORE = 100;
        $player->assists = 2;
        // ... (set other player properties here)

        // Invoke the getPlayerStats private method using reflection
        $playerStats = $this->invokeGetPlayerStats($player);

        // Test that the returned array has the correct data
        $this->assertSame(100, $playerStats['SCORE']);
        $this->assertSame(2, $playerStats['assists']);
        // ... (test other player properties here)
    }

    private function invokeGetPlayerStats($player): array
    {
        $reflectionClass = new ReflectionClass(ResultDataFormatter::class);
        $method = $reflectionClass->getMethod('getPlayerStats');
        $method->setAccessible(true);

        return $method->invoke(null, $player);
    }

    public function testGetPlayerData()
    {
        $player1 = new stdClass;
        $player1->SCORE = 1;
        // ... (set all other player properties)

        $player2 = new stdClass;
        $player2->SCORE = 2;
        // ... (set all other player properties)

        $players = new stdClass;
        $players->club1 = [$player1];
        $players->club2 = [$player2];

        $resultDataFormatter = new ReflectionClass(ResultDataFormatter::class);
        $getPlayerData = $resultDataFormatter->getMethod('getPlayerData');
        $getPlayerData->setAccessible(true);

        $result = $getPlayerData->invoke(null, $players);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertIsArray($result['club1']);
        $this->assertIsArray($result['club2']);
        $this->assertEquals(1, $result['club1'][0]['SCORE']);
        $this->assertEquals(2, $result['club2'][0]['SCORE']);
        // ... (assert other player properties)
    }
}
