<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\DashboardController;
use App\Models\Result;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    /**
     * @test
     */
    public function indexReturnsViewWithResults()
    {
        // Create 15 mock results
        $results = Result::factory(15)->create();

        // Call the index method of the DashboardController
        $controller = new DashboardController;
        $response = $controller->index();

        // Assert that the response is a view
        $this->assertInstanceOf(\Illuminate\View\View::class, $response);

        // Assert that the view has a 'results' variable
        $this->assertTrue($response->offsetExists('results'));

        // Assert that the 'results' variable is a paginator instance
        $this->assertInstanceOf(LengthAwarePaginator::class, $response['results']);

        // Assert that the paginator contains the correct number of results
        $this->assertEquals(10, $response['results']->count());

        // Assert that the paginator contains the correct results
        $expectedResults = $results->sortByDesc('created_at')->take(10);
        $actualResults = $response['results']->items();
        $this->assertEquals($expectedResults->pluck('id'), collect($actualResults)->pluck('id'));
    }
}
