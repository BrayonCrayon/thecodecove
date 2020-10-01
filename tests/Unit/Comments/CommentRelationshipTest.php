<?php

namespace Tests\Unit\Comments;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Tests\Utility;

class CommentRelationshipTest extends TestCase
{
    private $utility;

    /** @test */
    public function it_brings_back_user_that_created_the_comment()
    {
        $comment = Comment::all()->random();
        $user = User::findOrFail($comment->user_id);

        $this->assertEquals($user, $comment->user, "Comment user relationship did not bring back correct user object");
        $this->assertDatabaseHas('users', [
            'id'    => $comment->user->id,
            'name'  => $comment->user->name,
            'email' => $comment->user->email
        ]);
    }

    /** @test */
    public function it_brings_back_post_that_the_comment_is_attached_to()
    {
        $comment = Comment::isRootComment()->get()->random();
        $post = Post::findOrFail($comment->post_id);

        $this->assertEquals($post, $comment->post, "Comment post relationship did not bring back correct post object");
        $this->assertDatabaseHas('posts', [
            'id'        => $comment->post->id,
            'content'   => $comment->post->content,
            'user_id'   => $comment->post->user_id,
            'status_id' => $comment->post->status_id,
        ]);
    }

    /** @test */
    public function it_brings_back_comments_that_are_replied_to_a_comment()
    {
        $comment = Comment::isRootComment()->get()->first();
        $relationshipComments = Comment::isNestedComment()->parentIs($comment->id)->get();

        $relationshipComments->each(function ($item) use ($comment) {
            $this->assertEquals($item, $comment->comments->where('id', $item->id)->first());
        });

        $comment->comments->each(function ($item) {
            $this->assertDatabaseHas('comments', [
                'id'        => $item->id,
                'text'      => $item->text,
                'user_id'   => $item->user_id,
                'parent_id' => $item->parent_id,
                'post_id'   => $item->post_id,
            ]);
        });
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->utility = new Utility($this);
        $this->utility->testSetup();
    }
}
