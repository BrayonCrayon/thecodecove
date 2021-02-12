<?php

namespace Tests\Unit\Comments;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use tests\TestCase;
use Tests\Utility;

class DeleteCommentTest extends TestCase
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
    public function it_does_not_allow_non_logged_in_users_to_remove_comment()
    {
        $comment = Post::first()->comments()->first();
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
    public function it_does_allow_logged_in_users_to_remove_comments()
    {
        $this->utility->loginUser();
        $comment = Post::first()->comments()->first();
        $this->deleteJson(route('api.comment.delete', $comment->id))
            ->assertOk();

        $comment = Comment::onlyTrashed()->where('id', $comment->id)->first();
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'parent_id' => $comment->parent_id,
            'user_id' => $comment->user_id,
            'text' => $comment->text,
        ]);
        $this->assertNotNull($comment->deleted_at, "Did not soft delete the comment");
    }

    /** @test */
    public function it_does_allow_admin_user_to_remove_comments()
    {
        Carbon::setTestNow(now());
        $this->utility->loginAdmin();
        $comment = Post::first()->comments()->first();
        $this->deleteJson(route('api.comment.delete', $comment->id))
            ->assertOk();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'parent_id' => $comment->parent_id,
            'user_id' => $comment->user_id,
            'text' => $comment->text,
            'deleted_at' => now(),
        ]);
    }
}
