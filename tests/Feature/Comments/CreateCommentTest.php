<?php

namespace Tests\Feature\Comments;

use App\Models\Comment;
use App\Models\Post;
use Tests\TestCase;

class CreateCommentTest extends TestCase
{
    /** @test */
    public function it_does_allow_non_auth_user_to_create_a_comment()
    {
        $post = factory(Post::class)->create();
        $commentText = $this->faker->text;
        $this->postJson(route('api.comment.store'), [
            'post_id' => $post->id,
            'text'   => $commentText
        ])->assertUnauthorized();

        $this->assertDatabaseMissing('comments', [
            'post_id' => $post->id,
            'text'    => $commentText,
        ]);
    }

    /** @test */
    public function it_allows_admin_user_to_create_a_comment()
    {
        $user = $this->loginAdmin();
        $post = factory(Post::class)->create([
            'user_id' => $user->id,
        ]);
        $commentText = $this->faker->text;

        $this->postJson(route('api.comment.store'), [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'text'   => $commentText
        ])->assertOk();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'text'    => $commentText,
        ]);
    }

    /** @test */
    public function it_allows_auth_user_to_create_a_comment()
    {
        $user = $this->loginUser();
        $post = factory(Post::class)->create();
        $commentText = $this->faker->text;

        $this->postJson(route('api.comment.store'), [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'text'   => $commentText
        ])->assertOk();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'text'    => $commentText,
        ]);
    }

    /** @test */
    public function it_allows_user_to_reply_to_another_comment()
    {
        $user = $this->loginUser();
        $post = factory(Post::class)->create();
        $parentComment = factory(Comment::class)->create([
            'post_id' => $post->id,
        ]);
        $commentText = $this->faker->text;

        $this->postJson(route('api.comment.store'), [
            'user_id' => $user->id,
            'parent_id' => $parentComment->id,
            'text'   => $commentText
        ])->assertOk();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'parent_id' => $parentComment->id,
            'post_id' => null,
            'text'    => $commentText,
        ]);
    }
}
