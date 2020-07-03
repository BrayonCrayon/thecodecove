<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class FetchLoggedInUserTest extends TestCase
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
    public function it_allows_auth_user_to_get_logged_in_user()
    {
        Sanctum::actingAs(
            $this->utility->user,
            ['*']
        );

        $this->getJson(route('api.auth.user'))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $this->utility->user->id,
                'name' => $this->utility->user->name,
                'email' => $this->utility->user->email,
            ]);
    }

    /** @test */
    public function it_does_not_allow_non_auth_user_to_get_logged_in_user()
    {
        $this->getJson(route('api.auth.user'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

}
