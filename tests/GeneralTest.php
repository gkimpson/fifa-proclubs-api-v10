<?php

declare(strict_types=1);

namespace Tests;

class GeneralTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_load_the_homepage_successfully(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }
}
