<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use App\Models\Status;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class UpdatePostsTest extends TestCase
{
    const ALTERED_STRING = 'ALTERED_STRING';

    /** @test */
    public function it_does_not_allow_non_auth_users()
    {
        $post = factory(Post::class)->create();
        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
            'status_id' => $this->faker->randomElement(Status::STATUSES),
        ])->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_does_not_allow_non_admin_to_update_post()
    {
        $this->loginUser();
        $post = factory(Post::class)->create();
        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
            'status_id' => $this->faker->randomElement(Status::STATUSES),
        ])->assertNotFound();
    }

    /** @test */
    public function it_does_allow_admin_to_update_post()
    {
        $user = $this->loginAdmin();
        $post = factory(Post::class)->create([
            'user_id' => $user->id,
        ]);
        $post->name = $this->faker->name . self::ALTERED_STRING;
        $post->content = $this->faker->text . self::ALTERED_STRING;
        $post->status_id = Status::DRAFT;
        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $post->name,
            'content' => $post->content,
            'status_id' => $post->status_id,
        ])->assertOk()
        ->assertJsonFragment([
            'name' => $post->name,
            'content' => $post->content,
            'status_id' => $post->status_id
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'status_id' => $post->status_id
        ]);
    }

    /** @test */
    public function it_does_not_allow_undefined_parameters()
    {
        $post = factory(Post::class)->create();
        $this->loginAdmin();
        $this->putJson(route('api.posts.update', $post->id), [
            'name' => null,
            'content' => null,
            'status_id' => null,
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'status_id' => $post->status_id,
            'published_at' => $post->published_at,
        ]);
    }

    /** @test */
    public function it_does_not_allow_empty_parameters()
    {
        $post = factory(Post::class)->create();
        $name = $this->faker->name . self::ALTERED_STRING;
        $content = $this->faker->text . self::ALTERED_STRING;
        $status = $post->status_id === Status::DRAFT;

        $this->loginAdmin();
        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $name,
            'content' => '',
            'status_id' => $status,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => '',
            'content' => $content,
            'status_id' => $status,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $name,
            'content' => $content,
            'status_id' => '',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing('posts', [
            'name' => $name,
            'content' => $content,
            'status_id' => $status
        ]);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'status_id' => $post->status_id,
            'published_at' => $post->published_at,
        ]);
    }
}
