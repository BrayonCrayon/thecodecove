<?php

namespace Tests\Feature\Auth;

use Illuminate\Http\Response;
use Tests\TestCase;

class LoginSocialTest extends TestCase
{
    /** @test */
    public function it_brings_back_redirect_url_for_social_login()
    {
        $this->postJson(route('api.login.social'), [
            'social' => 'github',
        ])
        ->assertOk()
        ->assertJsonStructure([
            'targetUrl'
        ]);
    }

    //TODO: Not fully implemented Yet.
//    /** @test */
    public function it_rejects_user_if_credentials_are_incorrect_github()
    {
        $this->postJson(route('api.login.social'), [
            'social' => 'github',
            'username' => '',
            'email' => '',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
