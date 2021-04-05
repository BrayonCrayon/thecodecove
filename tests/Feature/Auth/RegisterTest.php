<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** @test */
    public function it_does_not_allow_non_auth_users()
    {
        $newUser = User::factory()->make();

        $this->postJson(route('register'), [
            'email' => $newUser->email,
            'name' => $newUser->name,
            'password' => $newUser->password,
            'password_confirmation' => $newUser->password,
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_allows_auth_user_to_register_new_users()
    {
        $user = $this->loginAdmin();
        $newUser = User::factory()->make();

        $this->postJson(route('register'), [
            'email' => $newUser->email,
            'name' => $newUser->name,
            'password' => $newUser->password,
            'password_confirmation' => $newUser->password,
        ])
            ->assertStatus(Response::HTTP_CREATED);

        $this->getJson(route('api.auth.user'))
            ->assertOk()
            ->assertJsonFragment([
                'email' => $user->email,
                'name' => $user->name,
            ]);
    }
}
