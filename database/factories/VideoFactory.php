<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'url' => $this->faker->url(),
            'description' => $this->faker->paragraph(),
            'type' => 'youtube',
            'is_published' => 1,
        ];
    }

    public function unPublished(){
        return $this->state(static function (array $attribute){
            return [
              'is_published' => 0
            ];
        });
    }
}
