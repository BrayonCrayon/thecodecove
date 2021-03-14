<?php

namespace Tests\Feature\Posts;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class CreatePostsTest extends TestCase
{

    /** @test */
    public function it_expects_certain_shape()
    {
        $user = $this->loginAdmin();
        $this->postJson(route('api.posts.store'), [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
            'userId' => $user->id,
        ])->assertJsonStructure([
                'id',
                'name',
                'content',
                'user_id',
                'status_id',
                'published_at',
                'created_at',
        ]);
    }

    /** @test */
    public function it_does_not_allow_non_auth_users()
    {
        $nonExistingUserId = 0;
        $this->postJson(route('api.posts.store'), [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
            'userId' => $nonExistingUserId,
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_does_not_allow_guest_to_create_post()
    {
        $user = $this->loginUser();
        $this->postJson(route('api.posts.store'), [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
            'userId' => $user->id,
        ])
            ->assertNotFound();
    }

    /** @test */
    public function it_brings_back_created_post()
    {
        $user = $this->loginAdmin();
        $name = $this->faker->name;
        $content = $this->faker->text;
        $this->postJson(route('api.posts.store'), [
            'name' => $name,
            'content' => $content,
            'userId' => $user->id,
        ])->assertJson([
            'name' => $name,
            'content' => $content,
            'status_id' => Status::DRAFT,
            'published_at' => null,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_does_allow_auth_users()
    {
        $user = $this->loginAdmin();
        $name = $this->faker->name;
        $content = $this->faker->text;
        $this->postJson(route('api.posts.store'), [
            'name' => $name,
            'content' => $content,
            'userId' => $user->id,
        ])->assertOk();

        $this->assertDatabaseHas('posts', [
            'name' => $name,
            'content' => $content,
            'user_id' => $user->id,
            'status_id' => Status::DRAFT,
            'published_at' => null
        ]);
    }

    /** @test */
    public function it_does_not_allow_non_existing_user()
    {
        $user = $this->loginAdmin();
        $name = $this->faker->name;
        $content = $this->faker->text;
        $nonExistingUserId = 0;
        $this->postJson(route('api.posts.store'), [
            'name' => $name,
            'content' => $content,
            'userId' => $nonExistingUserId,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing('posts', [
            'name' => $name,
            'content' => $content,
            'user_id' => $nonExistingUserId,
        ]);
    }

    /** @test */
    public function it_does_not_allow_empty_post_parameters()
    {
        $user = $this->loginAdmin();
        $name = $this->faker->name;
        $content = $this->faker->text;
        $this->postJson(route('api.posts.store'), [
            'name' => '',
            'content' => $content,
            'userId' => $user->id,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->postJson(route('api.posts.store'), [
            'name' => $name,
            'content' => '',
            'userId' => $user->id,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing('posts', [
            'name' => $name,
            'content' => $content,
            'user_id' => $user->id,
        ]);
    }
}
