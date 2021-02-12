<?php

namespace Tests\Unit\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class DeletePostsTest extends TestCase
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
    public function it_does_not_allow_non_auth_users_to_delete_post_from_db()
    {
        $post = Post::first();
        $this->deleteJson(route('api.posts.delete', $post->id))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'published_at' => $post->published_at,
        ]);
    }

    /** @test */
    public function it_does_allow_auth_user_to_delete_post_from_db()
    {
        $this->utility->loginAdmin();
        $post = Post::first();
        $this->deleteJson(route('api.posts.delete', $post->id))
            ->assertOk();

        $post = Post::onlyTrashed()->where('id', $post->id)->first();
        $this->assertNotNull($post->deleted_at, 'Posts deleted_at attribute did not get set.');
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'published_at' => $post->published_at,
        ]);
    }
}
