<?php

namespace Tests\Unit\Services;

use App\Services\ChartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChartServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testGetPlayerComparisonDataReturnsInvalidArgumentExceptionIfNotAttributes()
    {
        $clubId = 1;
        $platform = 'ps5';
        $player1 = 'Player 1';
        $player2 = 'Player 2';

        // TODO: Attributes (figure out how to set the attributes within the test
        $this->expectException(\Assert\InvalidArgumentException::class);
        $this->expectExceptionMessage('Attributes cannot be empty');

        ChartService::getPlayerComparisonData($clubId, $platform, $player1, $player2);
    }
}