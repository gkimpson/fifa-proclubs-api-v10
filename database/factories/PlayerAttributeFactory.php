<?php

namespace Database\Factories;

use App\Models\PlayerAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerAttributeFactory extends Factory
{
    protected $model = PlayerAttribute::class;

    public function definition()
    {
        return [
            'player_id' => $this->faker->numberBetween(1, 10000),
            'favourite_position' => $this->faker->randomElement(['G', 'D', 'M', 'F']),
            'acceleration' => $this->faker->numberBetween(1, 99),
            'aggression' => $this->faker->numberBetween(1, 99),
            'agility' => $this->faker->numberBetween(1, 99),
            'attack_position' => $this->faker->numberBetween(1, 99),
            'balance' => $this->faker->numberBetween(1, 99),
            'ball_control' => $this->faker->numberBetween(1, 99),
            'crossing' => $this->faker->numberBetween(1, 99),
            'curve' => $this->faker->numberBetween(1, 99),
            'dribbling' => $this->faker->numberBetween(1, 99),
            'finishing' => $this->faker->numberBetween(1, 99),
            'free_kick_accuracy' => $this->faker->numberBetween(1, 99),
            'gk_diving' => $this->faker->numberBetween(1, 99),
            'gk_handling' => $this->faker->numberBetween(1, 99),
            'gk_kicking' => $this->faker->numberBetween(1, 99),
            'gk_positioning' => $this->faker->numberBetween(1, 99),
            'gk_reflexes' => $this->faker->numberBetween(1, 99),
            'heading_accuracy' => $this->faker->numberBetween(1, 99),
            'interceptions' => $this->faker->numberBetween(1, 99),
            'jumping' => $this->faker->numberBetween(1, 99),
            'long_pass' => $this->faker->numberBetween(1, 99),
            'long_shots' => $this->faker->numberBetween(1, 99),
            'marking' => $this->faker->numberBetween(1, 99),
            'penalties' => $this->faker->numberBetween(1, 99),
            'reactions' => $this->faker->numberBetween(1, 99),
            'short_pass' => $this->faker->numberBetween(1, 99),
            'shot_power' => $this->faker->numberBetween(1, 99),
            'slide_tackle' => $this->faker->numberBetween(1, 99),
            'sprint_speed' => $this->faker->numberBetween(1, 99),
            'stamina' => $this->faker->numberBetween(1, 99),
            'stand_tackle' => $this->faker->numberBetween(1, 99),
            'strength' => $this->faker->numberBetween(1, 99),
            'unsure_attribute' => $this->faker->numberBetween(1, 99),
            'vision' => $this->faker->numberBetween(1, 99),
            'volleys' => $this->faker->numberBetween(1, 99),
        ];
    }
}
