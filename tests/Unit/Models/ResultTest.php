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
    public function singleResultCanBeAdded(): void
    {
        Result::factory()->create();
        $this->assertDatabaseCount('results', 1);
    }

    /**
     * @test
     */
    public function multipleResultsCanBeAdded(): void
    {
        $num_rows_to_insert = 10;
        Result::factory($num_rows_to_insert)->create();
        $this->assertDatabaseCount('results', $num_rows_to_insert);
    }

    /**
     * @test
     */
    public function resultsModelExistsInTheDatabase(): void
    {
        $result = Result::factory()->create();
        $this->assertModelExists($result);
    }

    /**
     * @test
     */
    public function singleResultCanBeAddedAndVerifiedInTheDatabase(): void
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
    public function errorExceptionReturnedIfMatchIdValueIncorrect(): void
    {
        $this->expectException(TypeError::class);
        Result::factory()->create([
            'match_id' => [],
        ]);
    }

    /** @test */
    public function itCanScopeByTeam()
    {
        $teamId = 1;
        Result::factory()->count(3)->create(['home_team_id' => $teamId]);
        Result::factory()->count(2)->create(['away_team_id' => $teamId]);

        $results = Result::byTeam($teamId)->get();

        $this->assertCount(5, $results);
        $results->each(function ($result) use ($teamId) {
            $this->assertTrue($result->home_team_id === $teamId || $result->away_team_id === $teamId);
        });
    }

    /** @test */
    public function itCanScopeHomeTeam()
    {
        $teamId = 1;
        Result::factory()->count(3)->create(['home_team_id' => $teamId]);
        Result::factory()->count(2)->create(['home_team_id' => 2]);

        $results = Result::homeTeam($teamId)->get();

        $this->assertCount(3, $results);
        $results->each(function ($result) use ($teamId) {
            $this->assertEquals($teamId, $result->home_team_id);
        });
    }

    /** @test */
    public function itCanScopeAwayTeam()
    {
        $teamId = 1;
        Result::factory()->count(3)->create(['away_team_id' => $teamId]);
        Result::factory()->count(2)->create(['away_team_id' => 2]);

        $results = Result::awayTeam($teamId)->get();

        $this->assertCount(3, $results);
        $results->each(function ($result) use ($teamId) {
            $this->assertEquals($teamId, $result->away_team_id);
        });
    }
}
