<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class ClubControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @test
     */
    public function example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
