<?php

namespace Tests\Feature\Comments;

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
    public function it_allows_non_logged_in_users_to_fetch_comments()
    {
        $post = Post::first();
        $response = $this->getJson(route('api.comments.root', $post->id))
            ->assertOk()
            ->assertJsonCount($post->comments()->count())
            ->assertJsonStructure([
                [
                    'id',
                    'text',
                    'parent_id',
                    'post_id',
                    'user_id',
                ]
            ]);

        $post->comments->each(function($comment) use($response) {
            $response->assertJsonFragment([
                'id' => $comment->id,
                'text' => $comment->text,
                'parent_id' => $comment->parent_id,
                'post_id' => $comment->post_id,
                'user_id' => $comment->user_id,
            ]);
        });
    }

    /** @test */
    public function it_allows_admins_to_fetch_comments_of_a_comment()
    {
        $this->utility->loginAdmin();
        $comment = Post::first()->comments()->first();
        $response = $this->getJson(route('api.comments.nested', $comment->id))
            ->assertOk()
            ->assertJsonCount($comment->comments()->count())
            ->assertJsonStructure([
                [
                    'id',
                    'text',
                    'parent_id',
                    'post_id',
                    'user_id',
                ]
            ]);

        $comment->comments->each(function($comment) use($response) {
            $response->assertJsonFragment([
                'id' => $comment->id,
                'text' => $comment->text,
                'parent_id' => $comment->parent_id,
                'post_id' => $comment->post_id,
                'user_id' => $comment->user_id,
            ]);
        });
    }

    /** @test */
    public function it_allows_guests_to_fetch_comments()
    {
        $this->utility->loginUser();
        $post = Post::first();
        $response = $this->getJson(route('api.comments.root', $post->id))
            ->assertOk()
            ->assertJsonCount($post->comments()->count())
            ->assertJsonStructure([
                [
                    'id',
                    'text',
                    'parent_id',
                    'post_id',
                    'user_id',
                ]
            ]);

        $post->comments->each(function($comment) use($response) {
            $response->assertJsonFragment([
                'id' => $comment->id,
                'text' => $comment->text,
                'parent_id' => $comment->parent_id,
                'post_id' => $comment->post_id,
                'user_id' => $comment->user_id,
            ]);
        });
    }
}
