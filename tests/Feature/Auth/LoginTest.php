<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @test */
    public function it_allows_user_to_login()
    {
        $user = User::factory()->create();
        $this->postJson(route('login'), [
            'email'    => $user->email,
            'password' => 'password',
        ])
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
