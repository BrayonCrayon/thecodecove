<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class DeletePostsTest extends TestCase
{
    /** @test */
    public function it_does_not_allow_non_auth_users_to_delete_post()
    {
        $post = Post::factory()->create();
        $this->deleteJson(route('api.posts.delete', $post->id))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_does_not_allow_a_user_to_delete_another_users_post()
    {
        $this->loginUser();
        $post = Post::factory()->create();
        $this->deleteJson(route('api.posts.delete', $post->id))
            ->assertNotFound();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'published_at' => $post->published_at,
        ]);
    }

    /** @test */
    public function it_does_allow_admin_users_to_delete_post()
    {
        $user = $this->loginAdmin();
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->deleteJson(route('api.posts.delete', $post->id))
            ->assertOK();

        $this->assertSoftDeleted('posts', [
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'user_id' => $post->user_id,
            'published_at' => $post->published_at
        ]);
    }
}
