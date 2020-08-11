<?php

namespace Tests\Unit\Comments;

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

    /** @test */
    public function it_does_allow_non_auth_user_to_create_a_comment()
    {
        $post = Post::all()->random();
        $commentText = $this->faker->text . '_TEST';
        $this->postJson(route('api.comment.store'), [
            'post_id' => $post->id,
            'text'   => $commentText
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertDatabaseMissing('comments', [
            'post_id' => $post->id,
            'text'    => $commentText,
        ]);
    }

    /** @test */
    public function it_allows_auth_user_to_create_a_comment()
    {
        $this->utility->loginAdmin();
        $post = Post::all()->random();
        $commentText = $this->faker->text;

        $this->postJson(route('api.comment.store'), [
            'user_id' => $this->utility->user->id,
            'post_id' => $post->id,
            'text'   => $commentText
        ])
            ->assertOk();

        $this->assertDatabaseHas('comments', [
            'user_id' => $this->utility->user->id,
            'post_id' => $post->id,
            'text'    => $commentText,
        ]);
    }

    /** @test */
    public function it_allows_users_to_reply_to_other_comments()
    {
        $this->utility->loginGuest();
        $post = Post::all()->random();
        $comment = $post->comments()->first();
        $commentText = $this->faker->text;

        $this->postJson(route('api.comment.store'), [
            'user_id' => $this->utility->user->id,
            'parent_id' => $comment->id,
            'text'      => $commentText
        ])
            ->assertOk();

        $this->assertDatabaseHas('comments', [
            'user_id' => $this->utility->user->id,
            'parent_id' => $comment->id,
            'post_id' => null,
            'text'    => $commentText,
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->utility = new Utility($this);
        $this->utility->testSetup();
    }
}
