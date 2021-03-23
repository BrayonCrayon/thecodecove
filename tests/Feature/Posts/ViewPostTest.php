<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use App\Models\Status;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ViewPostTest extends TestCase
{
    /** @test */
    public function it_allows_users_to_access_post()
    {
        $post = factory(Post::class)->create();
        $this->loginUser();
        $this->getJson(route('api.posts.view', $post->id))
            ->assertOk();
    }

    /** @test */
    public function it_allows_non_users_to_access_post()
    {
        $post = factory(Post::class)->create();
        $this->getJson(route('api.posts.view', $post->id))
            ->assertOk();
    }

    /** @test */
    public function it_allows_admin_users_to_access_post()
    {
        $post = factory(Post::class)->create();
        $this->loginAdmin();
        $this->getJson(route('api.posts.view', $post->id))
            ->assertOk();
    }

    /** @test */
    public function it_returns_published_post_in_expected_shape()
    {
        Carbon::setTestNow(now());
        $post = factory(Post::class)->create();
        $this->getJson(route('api.posts.view', $post->id))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'status_id' => $post->status_id,
            'published_at' => $post->published_at->toIso8601String(),
            'created_at' => $post->created_at->toIso8601String(),
            'updated_at' => $post->updated_at->toIso8601String(),
        ]);
    }

    /** @test */
    public function it_returns_drafted_post_in_expected_shape()
    {
        $post = factory(Post::class)->create([
            'status_id' => Status::DRAFT,
            'published_at' => null,
        ]);
        $this->getJson(route('api.posts.view', $post->id))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $post->id,
                'name' => $post->name,
                'content' => $post->content,
                'user_id' => $post->user_id,
                'status_id' => $post->status_id,
                'published_at' => null,
                'created_at' => $post->created_at->toIso8601String(),
                'updated_at' => $post->updated_at->toIso8601String(),
            ]);
    }

    /** @test */
    public function it_returns_related_comments_for_post()
    {
        $post = factory(Post::class)->create([
            'status_id' => Status::DRAFT,
            'published_at' => null,
        ]);
        $response = $this->getJson(route('api.posts.view', $post->id))
            ->assertOk();

        $post->comments->each(function($comment) use ($response) {
            $response->assertJsonFragment([
                'text' => $comment->text,
                'post_id' => $comment->post_id,
                'user_id' => $comment->user_id,
                'parent_id' => $comment->parent_id,
            ]);
        });
    }

    /** @test */
    public function it_does_not_allow_non_existing_post()
    {
        $nonExistingPostId = 0;
        $this->getJson(route('api.posts.view', [
            'post' => $nonExistingPostId,
        ]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
