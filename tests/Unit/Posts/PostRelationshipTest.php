<?php

namespace Tests\Unit\Posts;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Status;
use Tests\TestCase;

class PostRelationshipTest extends TestCase
{
    /** @test */
    public function it_brings_back_correct_user_from_posts_user_relationship()
    {
        $user = $this->loginUser();
        $post = factory(Post::class)->create([
            'user_id' => $user,
        ]);
        $this->assertEquals($user->id, $post->user->id);
        $this->assertDatabaseHas('users', [
            'id'    => $post->user->id,
            'name'  => $post->user->name,
            'email' => $post->user->email
        ]);
    }

    /** @test */
    public function it_brings_back_correct_status_from_posts_status_relationship()
    {
        $post = factory(Post::class)->create();
        $this->assertEquals(Status::PUBLISHED, $post->status->id);
        $this->assertDatabaseHas('statuses', [
            'id'   => $post->status->id,
            'name' => $post->status->name,
        ]);
    }

    /** @test */
    public function it_brings_back_correct_comments_from_posts_comment_relationship()
    {
        $post = factory(Post::class)->create();
        factory(Comment::class, 2)->create([
            'post_id' => $post->id,
        ]);
        $queriedComments = Comment::isRootComment()->postIs($post->id)->get();

        $queriedComments->each(function ($item) use ($post) {
            $this->assertEquals($item, $post->comments->where('id', $item->id)->first());
        });

        $post->comments->each(function ($item) {
            $this->assertDatabaseHas('comments', [
                'id'        => $item->id,
                'user_id'   => $item->user_id,
                'parent_id' => $item->parent_id,
                'post_id'   => $item->post_id,
                'text'      => $item->text,
            ]);
        });
    }
}
