<?php

namespace Tests\Unit\Models;

use App\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TypeError;

class ResultTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_single_result_can_be_added()
    {
        Result::factory()->create();
        $this->assertDatabaseCount('results', 1);
    }

    /**
     * @test
     */
    public function multiple_results_can_be_added()
    {
        $num_rows_to_insert = 10;
        Result::factory($num_rows_to_insert)->create();
        $this->assertDatabaseCount('results', $num_rows_to_insert);
    }

    /**
     * @test
     */
    public function results_model_exists_in_the_database()
    {
        $result = Result::factory()->create();
        $this->assertModelExists($result);
    }

    /**
     * @test
     */
    public function single_result_can_be_added_and_verified_in_the_database()
    {
        Result::factory()->create([
            'match_id' => 1234567890123,
            'home_team_id' => $this->clubId,
            'platform' => $this->platform,
        ]);

        $this->assertDatabaseCount('results', 1);
        $this->assertDatabaseHas('results', [
            'match_id' => 1234567890123,
            'home_team_id' => $this->clubId,
            'platform' => $this->platform,
        ]);
    }

    /**
     * @test
     */
    public function error_exception_returned_if_match_id_value_incorrect()
    {
        $this->expectException(TypeError::class);
        Result::factory()->create([
            'match_id' => [],
        ]);
    }
}
