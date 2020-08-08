<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class FetchDraftedPostsTest extends TestCase
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
    public function it_does_not_allow_non_auth_users_to_get_drafted_posts()
    {
        $this->getJson(route('api.posts.drafted'))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_does_allow_auth_user_to_get_drafted_posts()
    {
        Sanctum::actingAs(
            $this->utility->user,
            ['*']
        );

        $posts = Post::drafted()->get();
        $response = $this->getJson(route('api.posts.drafted'))
            ->assertOk()
            ->assertJsonStructure([
                [
                    'id',
                    'name',
                    'content',
                    'status_id',
                    'published_at',
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
