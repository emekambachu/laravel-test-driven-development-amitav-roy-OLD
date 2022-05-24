<?php

namespace Tests\Feature;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class VideoListingTest extends TestCase
{

    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_shows_list_of_videos()
    {
        Video::factory()->count(5)->create();

        $response = $this->json('GET', route('video.list'));

        $response->assertJson(static function (AssertableJson $json){
            $json->where('total', 5)
                ->has('data', 5)
                ->etc();
        });
    }

    public function test_shows_first_and_videos(){

        Video::factory()->count(12)->create();
        $response = $this->json('Get', route('video.list'));
        $response->assertJson(static function (AssertableJson $json){
            $json->where('total', 12)
                ->has('data', 10)
                ->has('data.0', function($video){
                    $video->where('is_published', "1")->etc();
                })->etc();
        });

    }

    public function test_shows_only_published_video(){

        Video::factory()->count(2)->unPublished()->create();
        Video::factory()->count(5)->create();

        $response = $this->json('Get', route('video.list'));
        $response->assertJson(static function (AssertableJson $json){
            $json->where('total', 5)
                ->has('data', 5)
                ->has('data.0', static function($video){
                    $video->where('is_published', "1")->etc();
                })->etc();
        });

    }
}
