<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithFaker;
    use DatabaseTransactions;

    public function loginAdmin()
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['admin']
        );
        $user->createToken($user->name . '-token', ['admin']);
        return $user;
    }

    public function loginUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        return $user;
    }
}
