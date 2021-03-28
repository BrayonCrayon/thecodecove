<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use App\Models\Status;
use Tests\TestCase;

class FetchDraftedPostsTest extends TestCase
{
    /** @test */
    public function it_does_not_allow_non_auth_users_to_get_drafted_posts()
    {
        $this->getJson(route('api.posts.drafted'))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_does_not_allow_auth_user_to_get_drafted_posts()
    {
        $this->loginUser();
        $this->getJson(route('api.posts.drafted'))
            ->assertNotFound();
    }

    /** @test */
    public function it_expects_certain_shape()
    {
        Post::factory()->count(5)->drafted()->create();
        $this->loginAdmin();
        $this->getJson(route('api.posts.drafted'))
            ->assertJsonStructure([
                'data' =>   [
                    [
                        'id',
                        'name',
                        'content',
                        'status_id',
                        'published_at',
                    ]
                ],
                'links' => [

                ]
            ]);
    }

    /** @test */
    public function it_does_allow_admin_user_to_get_drafted_posts()
    {
        $posts = Post::factory()->count(5)->drafted()->create();
        $this->loginAdmin();
        $response = $this->getJson(route('api.posts.drafted'))
            ->assertOk()
            ->assertJsonStructure([
                'data' =>   [
                    [
                        'id',
                        'name',
                        'content',
                        'status_id',
                        'published_at',
                    ]
                ],
                'links' => [

                ]
            ]);

        $posts->each( function($post) use ($response) {
            $response->assertJsonFragment([
                'id' => $post->id,
                'name' => $post->name,
                'content' => $post->content,
                'status_id' => $post->status_id,
                'published_at' => $post->published_at,
            ]);
        });
    }
}
