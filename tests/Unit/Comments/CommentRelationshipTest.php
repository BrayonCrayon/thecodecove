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
        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->create([
            'post_id' => $post->id,
        ]);
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
        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->create([
            'post_id' => $post->id,
        ]);
        $post = Post::findOrFail($comment->post_id);

        $this->assertEquals($post, $comment->post);
        $this->assertDatabaseHas('posts', [
            'id'        => $comment->post->id,
            'content'   => $comment->post->content,
            'user_id'   => $comment->post->user_id,
            'status_id' => $comment->post->status_id,
        ]);
    }

    /** @test */
    public function comment_can_have_many_comments()
    {
        $post = factory(Post::class)->create();
        $parentComment = factory(Comment::class)->create([
            'post_id' => $post->id,
        ]);
        $createdNestedComment = factory(Comment::class)->create([
           'parent_id' => $parentComment->id,
           'post_id' => null,
        ]);

        $childComment = $parentComment->comments()->first();
        $this->assertEquals($createdNestedComment->id, $childComment->id);
        $this->assertEquals($createdNestedComment->post_id, $childComment->post_id);
        $this->assertEquals($createdNestedComment->parent_id, $childComment->parent_id);
    }
}
