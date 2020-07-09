<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class LogoutTest extends TestCase
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
    public function it_does_not_allow_non_logged_in_users()
    {
        $this->getJson(route('api.logout'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_does_allow_auth_user_to_logout()
    {
        Sanctum::actingAs(
            $this->utility->user,
            ['*']
        );

        $this->postJson(route('logout'))
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
