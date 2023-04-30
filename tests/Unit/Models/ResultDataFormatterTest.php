<?php

namespace Tests\Unit\Models;

use App\Enums\Outcomes;
use App\Models\ResultDataFormatter;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use stdClass;

class ResultDataFormatterTest extends TestCase
{
    /**
     * @test
     */
    public function getMatchOutcome(): void
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

    /**
     * @test
     */
    public function getMatchOutcomeWithInvalidClubData(): void
    {
        // Test with invalid club data
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid club data provided.');

        $clubData = ['wins' => '0', 'losses' => '0', 'ties' => '0'];
        $this->invokeGetMatchOutcome($clubData);
    }

    /**
     * @test
     */
    public function getPlayerStats(): void
    {
        // Prepare the input player object
        $player = new stdClass;
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

    /**
     * @test
     */
    public function getPlayerData(): void
    {
        $player1 = new stdClass;
        $player1->SCORE = 1;
        $player1->cleansheetsany = 0;
        $player1->cleansheetsdef = 0;
        $player1->cleansheetsgk = 0;
        $player1->goals = 1;
        $player1->goalsconceded = 0;
        $player1->losses = 0;
        $player1->mom = 2;
        $player1->namespace = 1;
        $player1->passattempts = 10;
        $player1->passesmade = 5;
        $player1->playername = 'bobby';
        $player1->pos = 'forward';
        $player1->rating = '9.2';
        $player1->realtimegame = '905';
        $player1->redcards = 0;
        $player1->saves = 0;
        $player1->shots = 1;
        $player1->tackleattempts = 5;
        $player1->tacklesmade = 1;
        // phpcs:disable Generic.Files.LineLength.TooLong
        $player1->vproattr = '089|094|085|091|077|085|079|089|070|089|060|099|079|093|072|093|096|085|078|070|082|054|095|092|055|048|087|076|091|010|010|010|010|010|';
        $player1->vprohackreason = 8;
        $player1->wins = 1;
        $player1->assists = 2;

        $player2 = new stdClass;
        $player2->SCORE = 2;
        $player2->cleansheetsany = 0;
        $player2->cleansheetsdef = 0;
        $player2->cleansheetsgk = 0;
        $player2->goals = 1;
        $player2->goalsconceded = 0;
        $player2->losses = 0;
        $player2->mom = 2;
        $player2->namespace = 1;
        $player2->passattempts = 10;
        $player2->passesmade = 5;
        $player2->playername = 'peter';
        $player2->pos = 'midfielder';
        $player2->rating = '7.2';
        $player2->realtimegame = '800';
        $player2->redcards = 1;
        $player2->saves = 0;
        $player2->shots = 1;
        $player2->tackleattempts = 10;
        $player2->tacklesmade = 4;
        $player2->vproattr = '090|077|085|077|077|085|079|089|070|089|060|099|079|093|072|093|096|085|078|070|082|054|095|092|055|048|087|076|091|010|010|010|010|010|';
        $player2->vprohackreason = 8;
        $player2->wins = 1;
        $player2->assists = 0;

        $players = new stdClass;
        $players->club1 = [$player1];
        $players->club2 = [$player2];

        $resultDataFormatter = new ReflectionClass(ResultDataFormatter::class);
        $getPlayerData = $resultDataFormatter->getMethod('getPlayerData');

        $result = [];
        try {
            $result = $getPlayerData->invoke(null, $players);
        } catch (ReflectionException) {
        }

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertIsArray($result['club1']);
        $this->assertIsArray($result['club2']);
        $this->assertEquals(1, $result['club1'][0]['SCORE']);
        $this->assertEquals(2, $result['club2'][0]['SCORE']);
        $this->assertEquals($player1->cleansheetsany, $result['club1'][0]['cleansheetsany']);
        $this->assertEquals($player1->cleansheetsdef, $result['club1'][0]['cleansheetsdef']);
        $this->assertEquals($player1->cleansheetsgk, $result['club1'][0]['cleansheetsgk']);
        $this->assertEquals($player1->goals, $result['club1'][0]['goals']);
        $this->assertEquals($player1->goalsconceded, $result['club1'][0]['goalsconceded']);
        $this->assertEquals($player1->losses, $result['club1'][0]['losses']);
        $this->assertEquals($player1->mom, $result['club1'][0]['mom']);
        $this->assertEquals($player1->namespace, $result['club1'][0]['namespace']);
        $this->assertEquals($player1->passattempts, $result['club1'][0]['passattempts']);
        $this->assertEquals($player1->passesmade, $result['club1'][0]['passesmade']);
        $this->assertEquals($player1->playername, $result['club1'][0]['playername']);
        $this->assertEquals($player1->pos, $result['club1'][0]['pos']);
        $this->assertEquals($player1->rating, $result['club1'][0]['rating']);
        $this->assertEquals($player1->realtimegame, $result['club1'][0]['realtimegame']);
        $this->assertEquals($player1->redcards, $result['club1'][0]['redcards']);
        $this->assertEquals($player1->saves, $result['club1'][0]['saves']);
        $this->assertEquals($player1->shots, $result['club1'][0]['shots']);
        $this->assertEquals($player1->tackleattempts, $result['club1'][0]['tackleattempts']);
        $this->assertEquals($player1->tacklesmade, $result['club1'][0]['tacklesmade']);
        $this->assertEquals($player1->vproattr, $result['club1'][0]['vproattr']);
        $this->assertEquals($player1->vprohackreason, $result['club1'][0]['vprohackreason']);
        $this->assertEquals($player1->wins, $result['club1'][0]['wins']);
        $this->assertEquals($player1->assists, $result['club1'][0]['assists']);

        $this->assertEquals($player2->cleansheetsany, $result['club2'][0]['cleansheetsany']);
        $this->assertEquals($player2->cleansheetsdef, $result['club2'][0]['cleansheetsdef']);
        $this->assertEquals($player2->cleansheetsgk, $result['club2'][0]['cleansheetsgk']);
        $this->assertEquals($player2->goals, $result['club2'][0]['goals']);
        $this->assertEquals($player2->goalsconceded, $result['club2'][0]['goalsconceded']);
        $this->assertEquals($player2->losses, $result['club2'][0]['losses']);
        $this->assertEquals($player2->mom, $result['club2'][0]['mom']);
        $this->assertEquals($player2->namespace, $result['club2'][0]['namespace']);
        $this->assertEquals($player2->passattempts, $result['club2'][0]['passattempts']);
        $this->assertEquals($player2->passesmade, $result['club2'][0]['passesmade']);
        $this->assertEquals($player2->playername, $result['club2'][0]['playername']);
        $this->assertEquals($player2->pos, $result['club2'][0]['pos']);
        $this->assertEquals($player2->rating, $result['club2'][0]['rating']);
        $this->assertEquals($player2->realtimegame, $result['club2'][0]['realtimegame']);
        $this->assertEquals($player2->redcards, $result['club2'][0]['redcards']);
        $this->assertEquals($player2->saves, $result['club2'][0]['saves']);
        $this->assertEquals($player2->shots, $result['club2'][0]['shots']);
        $this->assertEquals($player2->tackleattempts, $result['club2'][0]['tackleattempts']);
        $this->assertEquals($player2->tacklesmade, $result['club2'][0]['tacklesmade']);
        $this->assertEquals($player2->vproattr, $result['club2'][0]['vproattr']);
        $this->assertEquals($player2->vprohackreason, $result['club2'][0]['vprohackreason']);
        $this->assertEquals($player2->wins, $result['club2'][0]['wins']);
        $this->assertEquals($player2->assists, $result['club2'][0]['assists']);
    }

    private function invokeGetMatchOutcome(array $clubData): string
    {
        $reflection = new ReflectionClass(ResultDataFormatter::class);
        $method = $reflection->getMethod('getMatchOutcome');

        // Create a new instance of the ResultDataFormatter class
        $resultDataFormatter = new ResultDataFormatter;

        // Invoke the getMatchOutcome method with the club data
        return $method->invokeArgs($resultDataFormatter, [$clubData]);
    }

    private function invokeGetPlayerStats(object $player): array
    {
        $reflectionClass = new ReflectionClass(ResultDataFormatter::class);
        $method = $reflectionClass->getMethod('getPlayerStats');

        return $method->invoke(null, $player);
    }
}
