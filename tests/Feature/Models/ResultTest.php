<?php

namespace Tests\Feature;

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
    public function singleResultCanBeAdded()
    {
        Result::factory()->create();
        $this->assertDatabaseCount('results', 1);
    }

    /**
     * @test
     */
    public function multipleResultsCanBeAdded()
    {
        $num_rows_to_insert = 10;
        Result::factory($num_rows_to_insert)->create();
        $this->assertDatabaseCount('results', $num_rows_to_insert);
    }

    /**
     * @test
     */
    public function modelExistsInTheDatabase()
    {
        $result = Result::factory()->create();
        $this->assertModelExists($result);
    }

    /**
     * @test
     */
    public function resultCanBeAddedAndVerifiedInDatabase()
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
    public function errorExceptionReturnedIfMatchIdValueIncorrect()
    {
        $this->expectException(TypeError::class);
        Result::factory()->create([
            'match_id' => [],
        ]);
    }

//    /**
//     * @test
//     * @dataProvider invalidData
//     */
//    public function testErrorExceptionReturned($invalidData): void
//    {
//        $this->expectException(\TypeError::class);
//        Result::factory()->create([
//            $invalidData
//        ]);
//    }
//
//    public function invalidData(): array
//    {
//        return [
//            [
//                'match_id' => []
//            ],
//            [
//                'match_id' => 123456789,
//                'home_team_id' => 'not-an-id'
//            ]
//        ];
//    }
}
