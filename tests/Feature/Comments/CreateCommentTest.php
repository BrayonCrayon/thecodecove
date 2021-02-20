<?php

namespace Tests\Feature\Comments;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class CreateCommentTest extends TestCase
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
    public function it_does_allow_non_auth_user_to_create_a_comment()
    {
        $post = Post::first();
        $commentText = $this->faker->text;
        $this->postJson(route('api.comment.store'), [
            'post_id' => $post->id,
            'text'   => $commentText
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_allows_admin_to_create_a_comment()
    {
        $this->utility->loginAdmin();
        $post = Post::first();
        $commentText = $this->faker->text;

        $this->postJson(route('api.comment.store'), [
            'user_id' => $this->utility->user->id,
            'post_id' => $post->id,
            'text'   => $commentText
        ])
            ->assertOk();
    }

    /** @test */
    public function it_allows_guests_to_reply_to_other_comments()
    {
        $this->utility->loginUser();
        $post = Post::first();
        $comment = $post->comments()->first();
        $commentText = $this->faker->text;

        $this->postJson(route('api.comment.store'), [
            'user_id' => $this->utility->user->id,
            'parent_id' => $comment->id,
            'text'   => $commentText
        ])
            ->assertOk();
    }
}
