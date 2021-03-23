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
        $posts = factory(Post::class, 5)->create();
        $posts->each( function($post) {
            factory(Comment::class, 2)->create([
                'post_id' => $post->id,
            ]);
        });
        $this->getJson(route('api.posts'))
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
        $post = factory(Post::class)->create();
        $this->getJson(route('api.posts'))
            ->assertJsonFragment([
                'id'           => $post->id,
                'name'         => $post->name,
                'content'      => $post->content,
                'published_at' => Carbon::parse($post->published_at)->toDateTimeString(),
                'status_id'    => $post->status_id,
                'created_at'   => $post->created_at,
                'updated_at'   => $post->updated_at,
            ]);
    }

    /** @test */
    public function it_brings_back_all_comments_for_posts()
    {
        $post = factory(Post::class)->create();
        $comments = factory(Comment::class, 5)->create([
            'post_id' => $post->id,
        ]);

        $response = $this->getJson(route('api.posts'));

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
