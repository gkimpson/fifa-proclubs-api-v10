<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\VideoController
 */
class VideoControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function indexDisplaysViewWithVideos(): void
    {
        $response = $this->get(route('video.index'));

        $response->assertOk();
        $response->assertViewIs('video.index');
        $response->assertViewHas('videos');
    }

    /**
     * @test
     */
    public function showDisplaysViewWithVideos(): void
    {
        $video = Video::factory()->create();

        $response = $this->get(route('video.show', $video));

        $response->assertOk();
        $response->assertViewIs('video.show');
        $response->assertViewHas('video');
    }
}
