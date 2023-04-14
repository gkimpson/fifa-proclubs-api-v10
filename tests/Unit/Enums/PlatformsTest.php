<?php

namespace Tests\Unit\Enums;

use App\Enums\Platforms;
use Exception;
use PHPUnit\Framework\TestCase;

class PlatformsTest extends TestCase
{
    public function test_all_returns_all_platform_names()
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

    public function test_generate_dropdown_values_returns_array_of_platforms()
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

    public function test_get_platform_returns_correct_platform()
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

    public function test_name_returns_correct_name()
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
