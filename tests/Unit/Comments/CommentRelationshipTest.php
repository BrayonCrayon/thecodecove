<?php

namespace Tests\Unit\Comments;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class CommentRelationshipTest extends TestCase
{
    /** @test */
    public function comment_belongs_to_a_user()
    {
        $comment = Comment::factory()->create();
        $user = User::findOrFail($comment->user_id);

        $this->assertEquals($user, $comment->user);
        $this->assertDatabaseHas('users', [
            'id'    => $comment->user->id,
            'name'  => $comment->user->name,
            'email' => $comment->user->email
        ]);
    }

    /** @test */
    public function root_comment_belongs_to_post()
    {
        $comment = Comment::factory()->create();
        $this->assertInstanceOf(Post::class, $comment->post);
    }

    /** @test */
    public function comment_can_have_many_comments()
    {
        $parentComment = Comment::factory()->hasComments(1, [
           'post_id' => null,
        ])->create();
        $this->assertInstanceOf(Comment::class, $parentComment->comments()->first());
    }
}
