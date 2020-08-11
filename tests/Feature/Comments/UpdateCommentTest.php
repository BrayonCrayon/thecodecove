<?php

namespace Tests\Feature\Comments;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
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
    public function it_does_not_allow_non_logged_in_users_to_update_comment()
    {
        $comment = Post::all()->random()->comments()->first();
        $newCommentText = $this->faker->text;

        $this->putJson(route('api.comment.update', $comment->id), [
            'text' => $newCommentText
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_does_allow_guests_to_update_comments()
    {
        $this->utility->loginGuest();
        $comment = Post::all()->random()->comments()->first();
        $newCommentText = $this->faker->text;

        $this->putJson(route('api.comment.update', $comment->id), [
            'text' => $newCommentText
        ])
            ->assertOk();
    }

    /** @test */
    public function it_does_allow_admin_to_update_comments()
    {
        $this->utility->loginAdmin();
        $comment = Post::all()->random()->comments()->first();
        $newCommentText = $this->faker->text;

        $this->putJson(route('api.comment.update', $comment->id), [
            'text' => $newCommentText
        ])
            ->assertOk();
    }
}
