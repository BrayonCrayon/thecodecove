<?php

namespace Tests\Feature\Posts;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Status;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tests\Utility;

class ViewPostTest extends TestCase
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
    public function it_allows_users_to_access_post()
    {
        $post = factory(Post::class)->create();
        $this->actingAs($this->utility->user)
            ->getJson(route('api.posts.view', $post->id))
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
    public function it_returns_published_post_in_expected_shape()
    {
        Carbon::setTestNow(now());
        $post = factory(Post::class)->create([
            'status_id' => Status::PUBLISHED,
            'published_at' => now(),
        ]);
        $this->getJson(route('api.posts.view', $post->id))
        ->assertOk()
        ->assertJsonFragment([
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'status_id' => $post->status_id,
            'published_at' => $post->published_at->toIso8601String(),
            'created_at' => $post->created_at,
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
                'created_at' => $post->created_at,
            ]);
    }

    /** @test */
    public function it_returns_related_comments_for_post()
    {
        $post = factory(Post::class)->create([
            'status_id' => Status::DRAFT,
            'published_at' => null,
        ]);
        $comment = factory(Comment::class)->create([
            'parent_id' => null,
            'post_id' => $post->id,
        ]);
        $this->getJson(route('api.posts.view', $post->id))
            ->assertOk()
            ->assertJsonFragment([
                'text' => $comment->text,
                'post_id' => $comment->post_id,
                'user_id' => $comment->user_id,
                'parent_id' => $comment->parent_id,
            ]);
    }
}
