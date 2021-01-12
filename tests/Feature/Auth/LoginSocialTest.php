<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Utility;

class LoginSocialTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;

    private $utility;

    protected function setUp(): void
    {
        parent::setUp();
        $this->utility = new Utility($this);
        $this->utility->testSetup();
    }

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
