<?php

namespace Tests\Feature\Auth;

use Illuminate\Http\Response;
use Tests\TestCase;

class FetchLoggedInUserTest extends TestCase
{

    /** @test */
    public function it_allows_auth_user_to_get_user_details()
    {
        $user = $this->loginAdmin();
        $this->getJson(route('api.auth.user'))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }

    /** @test */
    public function it_does_not_allow_non_auth_user_to_get_user_details()
    {
        $this->getJson(route('api.auth.user'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

}
