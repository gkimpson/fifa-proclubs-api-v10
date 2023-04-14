<?php

namespace Tests\Unit\Enums;

use App\Enums\Outcomes;
use PHPUnit\Framework\TestCase;

class OutcomesTest extends TestCase
{
    /** @test */
    public function it_returns_all_outcomes()
    {
        $outcomes = Outcomes::all();

        $this->assertIsArray($outcomes);
        $this->assertCount(3, $outcomes);
        $this->assertContains('homewin', $outcomes);
        $this->assertContains('awaywin', $outcomes);
        $this->assertContains('draw', $outcomes);
    }

    /** @test */
    public function it_returns_the_name_for_a_given_outcome()
    {
        $homeWinName = Outcomes::HOMEWIN->name();
        $awayWinName = Outcomes::AWAYWIN->name();
        $drawName = Outcomes::DRAW->name();

        $this->assertEquals('homewin', $homeWinName);
        $this->assertEquals('awaywin', $awayWinName);
        $this->assertEquals('draw', $drawName);
    }
}
