<?php

namespace Tests\Unit\Helpers;

use App\Helpers\PlayerAttributesHelper;
use Tests\TestCase;

class PlayerAttributesHelperTest extends TestCase
{
    /** @test */
    public function it_returns_a_valid_array_of_attribute_names()
    {
        $attributeNames = PlayerAttributesHelper::getPlayerAttributeNames();

        $this->assertIsArray($attributeNames);
        $this->assertCount(34, $attributeNames);
        $this->assertEquals('acceleration', $attributeNames[0]);
        $this->assertEquals('sprint_speed', $attributeNames[1]);
        $this->assertEquals('agility', $attributeNames[2]);
        $this->assertEquals('balance', $attributeNames[3]);
        $this->assertEquals('jumping', $attributeNames[4]);
        $this->assertEquals('stamina', $attributeNames[5]);
        $this->assertEquals('strength', $attributeNames[6]);
        $this->assertEquals('reactions', $attributeNames[7]);
        $this->assertEquals('aggression', $attributeNames[8]);
        $this->assertEquals('unsure_attribute', $attributeNames[9]);
        $this->assertEquals('interceptions', $attributeNames[10]);
        $this->assertEquals('attack_position', $attributeNames[11]);
        $this->assertEquals('vision', $attributeNames[12]);
        $this->assertEquals('ball_control', $attributeNames[13]);
        $this->assertEquals('crossing', $attributeNames[14]);
        $this->assertEquals('dribbling', $attributeNames[15]);
        $this->assertEquals('finishing', $attributeNames[16]);
        $this->assertEquals('free_kick_accuracy', $attributeNames[17]);
        $this->assertEquals('heading_accuracy', $attributeNames[18]);
        $this->assertEquals('long_pass', $attributeNames[19]);
        $this->assertEquals('short_pass', $attributeNames[20]);
        $this->assertEquals('marking', $attributeNames[21]);
        $this->assertEquals('shot_power', $attributeNames[22]);
        $this->assertEquals('long_shots', $attributeNames[23]);
        $this->assertEquals('stand_tackle', $attributeNames[24]);
        $this->assertEquals('slide_tackle', $attributeNames[25]);
        $this->assertEquals('volleys', $attributeNames[26]);
        $this->assertEquals('curve', $attributeNames[27]);
        $this->assertEquals('penalties', $attributeNames[28]);
        $this->assertEquals('gk_diving', $attributeNames[29]);
        $this->assertEquals('gk_handling', $attributeNames[30]);
        $this->assertEquals('gk_kicking', $attributeNames[31]);
        $this->assertEquals('gk_reflexes', $attributeNames[32]);
        $this->assertEquals('gk_positioning', $attributeNames[33]);
    }

    public function GetAttributeTailwindCssClasses()
    {
        // Test very_poor rating
        $result = PlayerAttributesHelper::getAttributeTailwindCssClass('20');
        $this->assertEquals('bg-red-500', $result);

        // Test poor rating
        $result = PlayerAttributesHelper::getAttributeTailwindCssClass('45');
        $this->assertEquals('bg-red-500', $result);

        // Test fair rating
        $result = PlayerAttributesHelper::getAttributeTailwindCssClass('60');
        $this->assertEquals('bg-yellow-500', $result);

        // Test good rating
        $result = PlayerAttributesHelper::getAttributeTailwindCssClass('75');
        $this->assertEquals('bg-green-500', $result);

        // Test very_good rating
        $result = PlayerAttributesHelper::getAttributeTailwindCssClass('85');
        $this->assertEquals('bg-green-500', $result);

        // Test excellent rating
        $result = PlayerAttributesHelper::getAttributeTailwindCssClass('95');
        $this->assertEquals('bg-green-500', $result);
    }
}
