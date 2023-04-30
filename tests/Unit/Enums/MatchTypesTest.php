<?php

namespace Tests\Unit\Enums;

use App\Enums\MatchTypes;
use PHPUnit\Framework\TestCase;

class MatchTypesTest extends TestCase
{
    /** @test */
    public function itReturnsAllMatchTypes(): void
    {
        $matchTypes = MatchTypes::all();

        $this->assertIsArray($matchTypes);
        $this->assertCount(2, $matchTypes);
        $this->assertContains('gameType9', $matchTypes);
        $this->assertContains('gameType13', $matchTypes);
    }

    /** @test */
    public function itReturnsTheNameForAGivenMatchType(): void
    {
        $leagueName = MatchTypes::LEAGUE->name();
        $cupName = MatchTypes::CUP->name();

        $this->assertEquals('gameType9', $leagueName);
        $this->assertEquals('gameType13', $cupName);
    }
}
