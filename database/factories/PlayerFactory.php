<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'club_id' => $this->faker->numberBetween(11111, 99999),
            'ea_player_id' => $this->faker->numberBetween(11111, 99999),
            'platform' => $this->faker->randomElement(['pc', 'ps4', 'ps5', 'xboxone', 'xbox-series-xs']),
            'player_name' => $this->faker->userName(),
            'attributes' => '091|092|087|090|079|085|077|089|070|089|060|099|075|093|072|094|096|084|083|068|080|054|094|091|055|048|089|076|089|010|010|010|010|010|',
        ];
    }
}
