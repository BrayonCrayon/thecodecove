<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class RegisterTest extends TestCase
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
    public function it_does_not_allow_non_auth_users()
    {
        $newUser = Factory(User::class)->make();

        $this->postJson(route('register'), [
            'email' => $newUser->email,
            'name' => $newUser->name,
            'password' => $newUser->password,
            'password_confirmation' => $newUser->password,
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_does_allow_auth_user_to_register_new_users()
    {
        $this->utility->loginAdmin();
        $newUser = Factory(User::class)->make();

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
                'email' => $this->utility->user->email,
                'name' => $this->utility->user->name,
            ]);
    }
}
