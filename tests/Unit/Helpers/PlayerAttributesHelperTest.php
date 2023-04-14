<?php

namespace Tests\Unit\Helpers;

use App\Helpers\PlayerAttributesHelper;
use PHPUnit\Framework\TestCase;

class PlayerAttributesHelperTest extends TestCase
{
    /** @test */
    public function it_returns_an_array_of_attribute_names()
    {
        $attributeNames = PlayerAttributesHelper::getPlayerAttributeNames();

        $this->assertIsArray($attributeNames);
        $this->assertCount(34, $attributeNames);
        $this->assertEquals('acceleration', $attributeNames[0]);
        $this->assertEquals('sprint_speed', $attributeNames[1]);
        $this->assertEquals('agility', $attributeNames[2]);
        // ... other attribute name assertions
        $this->assertEquals('gk_reflexes', $attributeNames[32]);
        $this->assertEquals('gk_positioning', $attributeNames[33]);
    }
}
