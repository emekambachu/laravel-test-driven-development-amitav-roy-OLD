<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_sends_user_token_on_correct_credentials(): void
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->json('POST', route('user.login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertStatus(200)->assertJson(static function (AssertableJson $json) use ($user){
            $json->has('token')
                ->where('user_name', $user->name)
                ->etc();
        });
    }

    public function test_validates_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->json('POST', route('user.login'), [
           'email' => $user->email,
           'password' => 'random',
        ])->assertStatus(422)->assertJson(static function (AssertableJson $json) use ($user){
            $json->has('errors')
                ->where('errors.email.0', __('auth.wrong_password'))
                ->etc();
        });
    }
}
