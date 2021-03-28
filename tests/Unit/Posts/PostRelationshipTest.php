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
        $post = Post::factory()->create([
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
        $post = Post::factory()->create();
        $this->assertEquals(Status::PUBLISHED, $post->status->id);
        $this->assertDatabaseHas('statuses', [
            'id'   => $post->status->id,
            'name' => $post->status->name,
        ]);
    }

    /** @test */
    public function it_has_many_comments()
    {
        $commentCount = 5;
        $post = Post::factory()->hasComments($commentCount)->create();
        $this->assertInstanceOf(Comment::class, $post->comments->first());
        $this->assertEquals($commentCount, $post->comments->count());
    }
}
