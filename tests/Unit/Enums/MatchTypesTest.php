<?php

namespace Tests\Unit\Enums;

use App\Enums\MatchTypes;
use PHPUnit\Framework\TestCase;

class MatchTypesTest extends TestCase
{
    /** @test */
    public function it_returns_all_match_types()
    {
        $matchTypes = MatchTypes::all();

        $this->assertIsArray($matchTypes);
        $this->assertCount(2, $matchTypes);
        $this->assertContains('gameType9', $matchTypes);
        $this->assertContains('gameType13', $matchTypes);
    }

    /** @test */
    public function it_returns_the_name_for_a_given_match_type()
    {
        $leagueName = MatchTypes::LEAGUE->name();
        $cupName = MatchTypes::CUP->name();

        $this->assertEquals('gameType9', $leagueName);
        $this->assertEquals('gameType13', $cupName);
    }
}
