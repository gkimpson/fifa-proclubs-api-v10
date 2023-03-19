<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Outcomes;
use App\Enums\Platforms;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'match_id' => $this->faker->randomNumber(9),
            'home_team_id' => $this->faker->randomNumber(3),
            'away_team_id' => $this->faker->randomNumber(3),
            'home_team_goals' => $this->faker->randomNumber(1),
            'away_team_goals' => $this->faker->randomNumber(1),
            'home_team_player_count' => $this->faker->numberBetween(2, 10),
            'away_team_player_count' => $this->faker->numberBetween(2, 10),
            'outcome' => $this->faker->randomElement(Outcomes::all()),
            'match_date' => $this->faker->dateTimeBetween('-3 months', 'now', null),
            'platform' => $this->faker->randomElement(Platforms::all()),
            'properties' => '{}',
        ];
    }
}
