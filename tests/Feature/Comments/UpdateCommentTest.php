<?php

namespace Tests\Feature\Comments;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateCommentTest extends TestCase
{
    const ALTERED_TEXT = "this is an altered text";

    /** @test */
    public function it_does_not_allow_non_auth_users_to_update_comment()
    {
        $comment = Comment::factory()->create();
        $this->putJson(route('api.comment.update', $comment->id), [
            'text' => self::ALTERED_TEXT
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'text' => $comment->text,
        ]);
    }

    /** @test */
    public function it_does_allow_auth_users_to_update_comments()
    {
        $comment = Comment::factory()->create();
        $this->loginUser();
        $this->putJson(route('api.comment.update', $comment->id), [
            'text' => self::ALTERED_TEXT
        ])
            ->assertOk();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'text' => self::ALTERED_TEXT
        ]);
    }

    /** @test */
    public function it_does_allow_admin_users_to_update_comments()
    {
        $comment = Comment::factory()->create();
        $this->loginAdmin();
        $this->putJson(route('api.comment.update', $comment->id), [
            'text' => self::ALTERED_TEXT
        ])
            ->assertOk();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'text' => self::ALTERED_TEXT
        ]);
    }
}
