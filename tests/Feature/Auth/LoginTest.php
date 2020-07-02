<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Utility;

class LoginTest extends TestCase
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
    public function it_allows_user_to_login()
    {
        $response = $this->get('sanctum/csrf-cookie')
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->postJson(route('api.login'), [
            'email'    => $this->utility->user->email,
            'password' => $this->utility->user->password,
        ], [
                'headers' => [
                    'referer' => 'localhost:3000',
                    'cookies' => $response->headers->getCookies()
                ]
            ]
        )
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
