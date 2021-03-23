<?php

namespace Tests;

use App\Models\Post;
use App\Models\Status;
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
        $user = factory(User::class)->create();
        Sanctum::actingAs(
            $user,
            ['admin']
        );
        $user->createToken($user->name . '-token', ['admin']);
        return $user;
    }

    public function loginUser()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);
        return $user;
    }

    public function CreateDraftedPosts($creationCount = 1)
    {
        factory(Post::class, 5)->create([
            'status_id' => Status::DRAFT,
            'published_at' => null
        ]);
    }
}
