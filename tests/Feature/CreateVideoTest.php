<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreateVideoTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_creates_new_video(): void
    {
        $url = $this->faker->url;
        $user = User::factory()->create();

        $this->actingAs($user)->json('POST', route('video.add'), [
            'url' => $url,
            'description' => 'test',
        ]);

        $this->assertDatabaseHas('videos', [
           'url' => $url,
           'description' => 'test'
        ]);
    }

    public function test_returns_video_in_response(): void
    {
        $url = $this->faker->url;
        $user = User::factory()->create();

        $response = $this->actingAs($user)->json('POST', route('video.add'), [
           'url' => $url
        ]);

        $response->assertJson(static function (AssertableJson $json) use ($url){
            $json->where('id', 1)
                ->where('url', $url)
                ->where('type', 'youtube')
                ->etc();
        });
    }
}
