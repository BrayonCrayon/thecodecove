<?php

namespace Tests\Unit\Posts;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Status;
use App\Models\User;
use Tests\TestCase;
use Tests\Utility;

class PostRelationshipTest extends TestCase
{
    private $utility;

    /** @test */
    public function it_brings_back_correct_user_from_posts_user_relationship()
    {
        $post = Post::all()->random();
        $queriedUser = User::findOrFail($post->user_id);

        $this->assertEquals($queriedUser, $post->user, "Post user relationship did not bring back correct user object");
        $this->assertDatabaseHas('users', [
            'id'    => $post->user->id,
            'name'  => $post->user->name,
            'email' => $post->user->email
        ]);
    }

    /** @test */
    public function it_brings_back_correct_status_from_posts_status_relationship()
    {
        $post = Post::all()->random();
        $queriedStatus = Status::findOrFail($post->status_id);

        $this->assertEquals($queriedStatus, $post->status, "Post status relationship did not bring back correct status object");
        $this->assertDatabaseHas('statuses', [
            'id'   => $post->status->id,
            'name' => $post->status->name,
        ]);
    }

    /** @test */
    public function it_brings_back_correct_comments_from_posts_comment_relationship()
    {
        $post = Post::all()->random();
        $queriedComments = Comment::isRootComment()->postIs($post->id)->get();

        $queriedComments->each(function ($item) use ($post) {
            $this->assertEquals($item, $post->comments->where('id', $item->id)->first(), "Post did not bring back correct comment from the comment relationship");
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->utility = new Utility($this);
        $this->utility->testSetup();
    }
}
