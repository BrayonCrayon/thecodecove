<?php

namespace Tests\Unit\Comments;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Utility;

class FetchCommentsTest extends TestCase
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
    public function it_brings_back_all_root_post_comments()
    {
        $this->utility->loginAdmin();
        $post = Post::first();

        $response = $this->getJson(route('api.comments.root', $post->id))
            ->assertOk();

        $this->assertEquals($post->comments->count(), $response->getOriginalContent()->count(), "Didn't bring back all root comments for the post.");
        $post->comments->each(function ($comment) use ($response) {
            $response->assertJsonFragment([
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'text' => $comment->text,
                'parent_id' => $comment->parent_id,
                'post_id' => $comment->post_id,
            ]);
        });

        $response->getoriginalContent()->each(function($comment) {
            $this->assertDatabaseHas('comments', [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'text' => $comment->text,
                'parent_id' => $comment->parent_id,
                'post_id' => $comment->post_id,
            ]);
        });
    }

    /** @test */
    public function it_brings_back_nested_post_comments()
    {
        $this->utility->loginAdmin();
        $post = Post::first();
        $comment = $post->comments()->first();

        $response = $this->getJson(route('api.comments.nested', $comment->id))
            ->assertOk();

        $this->assertEquals($comment->comments()->count(), $response->getOriginalContent()->count(), "Didn't bring back nested comments of a comment");
        $comment->comments->each(function ($comment) use ($response) {
            $response->assertJsonFragment([
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'text' => $comment->text,
                'parent_id' => $comment->parent_id,
                'post_id' => $comment->post_id,
            ]);
        });

        $response->getoriginalContent()->each(function($comment) {
            $this->assertDatabaseHas('comments', [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'text' => $comment->text,
                'parent_id' => $comment->parent_id,
                'post_id' => $comment->post_id,
            ]);
        });
    }

}
