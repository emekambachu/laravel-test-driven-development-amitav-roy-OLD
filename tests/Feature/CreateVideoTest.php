<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CreateVideoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_creates_new_video(): void
    {
        $this->withoutExceptionHandling();

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
        $this->withoutExceptionHandling();

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

    public function test_add_current_user_id_in_video(): void
    {
        User::factory()->count(5)->create();
        $user = User::factory()->create();
        $url = $this->faker->url;

        $this->actingAs($user)->json('POST', route('video.add'), [
            'url' => $url,
            'description' => 'test',
        ]);

        $this->assertDatabaseHas('videos', [
            'url' => $url,
            'description' => 'test',
            'user_id' => $user->id,
        ]);
    }

    public function test_allow_only_logged_in_users(): void
    {
        $this->json('GET', route('video.list'))
            ->assertStatus(401);
    }

}
