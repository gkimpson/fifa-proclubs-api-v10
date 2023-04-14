<?php

namespace App\Helpers;

class PlayerAttributesHelper
{
    public static function getPlayerAttributeNames()
    {
        return [
            0 => 'acceleration',
            1 => 'sprint_speed',
            2 => 'agility',
            3 => 'balance',
            4 => 'jumping',
            5 => 'stamina',
            6 => 'strength',
            7 => 'reactions',
            8 => 'aggression',
            9 => 'unsure_attribute',    // TODO - find out what this attribute is
            10 => 'interceptions',
            11 => 'attack_position',
            12 => 'vision',
            13 => 'ball_control',
            14 => 'crossing',
            15 => 'dribbling',
            16 => 'finishing',
            17 => 'free_kick_accuracy',
            18 => 'heading_accuracy',
            19 => 'long_pass',
            20 => 'short_pass',
            21 => 'marking',
            22 => 'shot_power',
            23 => 'long_shots',
            24 => 'stand_tackle',
            25 => 'slide_tackle',
            26 => 'volleys',
            27 => 'curve',
            28 => 'penalties',
            29 => 'gk_diving',
            30 => 'gk_handling',
            31 => 'gk_kicking',
            32 => 'gk_reflexes',
            33 => 'gk_positioning',
        ];
    }
}