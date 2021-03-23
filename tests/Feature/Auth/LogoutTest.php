<?php

namespace Tests\Feature\Auth;

use Illuminate\Http\Response;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /** @test */
    public function it_does_not_allow_non_logged_in_users()
    {
        $this->postJson(route('logout'))
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_does_allow_auth_user_to_logout()
    {
        $this->loginAdmin();
        $this->postJson(route('logout'))
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
