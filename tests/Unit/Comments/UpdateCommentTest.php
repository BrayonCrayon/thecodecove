<?php

namespace Tests\Unit\Comments;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Utility;

class UpdateCommentTest extends TestCase
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
    public function it_does_not_allow_empty_text_data()
    {
        $this->utility->loginAdmin();
        $comment = Post::all()->random()->comments()->first();

        $this->putJson(route('api.comment.update', $comment->id), [
            'text' => ''
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_does_not_allow_undefined_text_data()
    {
        $this->utility->loginAdmin();
        $comment = Post::all()->random()->comments()->first();

        $this->putJson(route('api.comment.update', $comment->id), [
            'text' => null,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_successfully_updates_comment()
    {
        $this->utility->loginAdmin();
        $newCommentText = $this->faker->text . '_TEST';
        $comment = Post::all()->random()->comments()->first();

        $this->putJson(route('api.comment.update', $comment->id), [
            'text' => $newCommentText,
        ])
            ->assertOk();

        $commentAfterRequest = Comment::findOrFail($comment->id);
        $this->assertEquals($newCommentText, $commentAfterRequest->text, "Comment text was not updated.");
    }
}
