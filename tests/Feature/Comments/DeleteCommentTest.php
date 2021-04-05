<?php

namespace Tests\Feature\Comments;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteCommentTest extends TestCase
{
    /** @test */
    public function it_does_not_allow_non_auth_to_remove_comment()
    {
        $comment = Comment::factory()->create();
        $this->deleteJson(route('api.comment.delete', $comment->id))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'parent_id' => $comment->parent_id,
            'user_id' => $comment->user_id,
            'text' => $comment->text,
        ]);
    }

    /** @test */
    public function it_does_allow_auth_user_to_remove_comments()
    {
        $this->loginUser();
        $comment = Comment::factory()->create();
        $this->deleteJson(route('api.comment.delete', $comment->id))
            ->assertOk();

        $this->assertSoftDeleted('comments', [
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'parent_id' => $comment->parent_id,
            'user_id' => $comment->user_id,
            'text' => $comment->text,
        ]);
    }

    /** @test */
    public function it_does_allow_admin_user_to_remove_comments()
    {
        $this->loginAdmin();
        $comment = Comment::factory()->create();
        $this->deleteJson(route('api.comment.delete', $comment->id))
            ->assertOk();

        $this->assertSoftDeleted('comments', [
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'parent_id' => $comment->parent_id,
            'user_id' => $comment->user_id,
            'text' => $comment->text,
        ]);
    }
}
