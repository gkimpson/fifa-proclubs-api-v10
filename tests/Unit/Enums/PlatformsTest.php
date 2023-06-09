<?php

namespace Tests\Unit\Enums;

use App\Enums\Platforms;
use Exception;
use PHPUnit\Framework\TestCase;

class PlatformsTest extends TestCase
{
    /**
     * @test
     */
    public function allReturnsAllPlatformNames(): void
    {
        $allPlatforms = Platforms::all();

        $this->assertIsArray($allPlatforms);
        $this->assertCount(5, $allPlatforms);
        $this->assertContains('pc', $allPlatforms);
        $this->assertContains('ps4', $allPlatforms);
        $this->assertContains('ps5', $allPlatforms);
        $this->assertContains('xboxone', $allPlatforms);
        $this->assertContains('xbox-series-xs', $allPlatforms);
    }

    /**
     * @test
     */
    public function generateDropdownValuesReturnsArrayOfPlatforms(): void
    {
        $dropdownValues = Platforms::generateDropdownValues();

        $this->assertIsArray($dropdownValues);
        $this->assertCount(5, $dropdownValues);

        $this->assertArrayHasKey('pc', $dropdownValues);
        $this->assertEquals('PC', $dropdownValues['pc']);
        $this->assertArrayHasKey('ps4', $dropdownValues);
        $this->assertEquals('PS4', $dropdownValues['ps4']);
        $this->assertArrayHasKey('ps5', $dropdownValues);
        $this->assertEquals('PS5', $dropdownValues['ps5']);
        $this->assertArrayHasKey('xboxone', $dropdownValues);
        $this->assertEquals('XBOX ONE', $dropdownValues['xboxone']);
        $this->assertArrayHasKey('xbox-series-xs', $dropdownValues);
        $this->assertEquals('XBOX SERIES SX', $dropdownValues['xbox-series-xs']);
    }

    /**
     * @test
     *
     * @throws Exception
     */
    public function getPlatformReturnsCorrectPlatform(): void
    {
        $pc = Platforms::getPlatform('pc');
        $this->assertEquals(Platforms::PC, $pc);

        $ps4 = Platforms::getPlatform('ps4');
        $this->assertEquals(Platforms::PS4, $ps4);

        $ps5 = Platforms::getPlatform('ps5');
        $this->assertEquals(Platforms::PS5, $ps5);

        $xboxOne = Platforms::getPlatform('xboxone');
        $this->assertEquals(Platforms::XBOX_ONE, $xboxOne);

        $xboxSeriesSx = Platforms::getPlatform('xbox-series-xs');
        $this->assertEquals(Platforms::XBOX_SERIES_SX, $xboxSeriesSx);

        $this->expectException(Exception::class);
        Platforms::getPlatform('invalid_platform');
    }

    /**
     * @test
     */
    public function nameReturnsCorrectName(): void
    {
        $pc = Platforms::PC;
        $this->assertEquals('pc', $pc->name());

        $ps4 = Platforms::PS4;
        $this->assertEquals('ps4', $ps4->name());

        $ps5 = Platforms::PS5;
        $this->assertEquals('ps5', $ps5->name());

        $xboxOne = Platforms::XBOX_ONE;
        $this->assertEquals('xboxone', $xboxOne->name());

        $xboxSeriesSx = Platforms::XBOX_SERIES_SX;
        $this->assertEquals('xbox-series-xs', $xboxSeriesSx->name());
    }
}
