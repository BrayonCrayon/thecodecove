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
        $this->postJson(route('login'), [
            'email'    => $this->utility->user->email,
            'password' => 'password',
        ])
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function it_does_not_allow_user_to_login_with_invalid_credentials()
    {
        $this->postJson(route('login'), [
            'email' => $this->faker->email,
            'password' => $this->faker->word,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonFragment([
            "message" => "The given data was invalid.",
            "errors" => [
                "email" => [
                    "These credentials do not match our records."
                ]
            ]
        ]);

        $this->assertNull(auth()->user(), "A user was logged in with invalid credentials.");
    }
}
