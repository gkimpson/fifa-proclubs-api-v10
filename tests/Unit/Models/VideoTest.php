<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $video->user);
        $this->assertEquals($user->id, $video->user->id);
    }

    /** @test */
    public function it_is_mass_assignable()
    {
        $data = [
            'user_id' => User::factory()->create()->id,
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'url' => $this->faker->url(),
        ];

        $video = new Video($data);

        $this->assertEquals($data, $video->getAttributes());
    }

    /** @test */
    public function test_video_belongs_to_user()
    {
        // Create a new user
        $user = User::factory()->create();

        // Create a new video belonging to the user
        $video = Video::factory()->create([
            'user_id' => $user->id,
        ]);

        // Get the user associated with the video
        $videoUser = $video->user;

        // Assert that the user is an instance of User model
        $this->assertInstanceOf(User::class, $videoUser);

        // Assert that the user has the correct ID
        $this->assertEquals($user->id, $videoUser->id);
    }
}
