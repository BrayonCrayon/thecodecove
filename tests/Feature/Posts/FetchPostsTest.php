<?php

namespace Tests\Feature\Posts;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class FetchPostsTest extends TestCase
{

    /** @test */
    public function it_returns_specific_shape()
    {
        Post::factory()->hasComments()->create();
        $this->getJson(route('api.posts'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'name',
                        'content',
                        'user_id',
                        'status_id',
                        'published_at',
                        'created_at',
                        'comments' => [
                            [
                                'id',
                                'post_id',
                                'parent_id',
                                'user_id',
                                'text'
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_allows_non_users_to_access_posts()
    {
        $this->getJson(route('api.posts'))
            ->assertOk();
    }

    /** @test */
    public function it_allows_users_to_access_posts()
    {
        $this->loginAdmin();
        $this->getJson(route('api.posts'))
            ->assertOk();
    }

    /** @test */
    public function it_brings_back_only_published_posts()
    {
        Carbon::setTestNow();
        $post = Post::factory()->create();
        $this->getJson(route('api.posts'))
            ->assertOk()
            ->assertJsonFragment([
                'id'           => $post->id,
                'name'         => $post->name,
                'content'      => $post->content,
                'published_at' => Carbon::parse($post->published_at)->toIso8601String(),
                'status_id'    => $post->status_id,
                'created_at'   => Carbon::parse($post->created_at)->toIso8601String(),
                'updated_at'   => Carbon::parse($post->updated_at)->toIso8601String(),
            ]);
    }

    /** @test */
    public function it_brings_back_all_comments_for_posts()
    {
        $comments = Comment::factory()->count(5)->forPost()->create();
        $response = $this->getJson(route('api.posts'))
        ->assertOk();

        $comments->each(function ($item) use ($response) {
            $response->assertJsonFragment([
                'id' => $item->id,
                'post_id' => $item->post_id,
                'parent_id' => $item->parent_id,
                'user_id' => $item->user_id,
                'text' => $item->text
            ]);
        });
    }
}
