<?php

namespace Tests\Feature\Comments;

use App\Models\Comment;
use App\Models\Post;
use Tests\TestCase;

class FetchCommentsTest extends TestCase
{
    /** @test */
    public function it_returns_expected_shape()
    {
        $commentCount = 5;
        $post = Post::factory()->create();
        Comment::factory()->count($commentCount)->create([
            'post_id' => $post->id,
        ]);
        $this->getJson(route('api.comments.root', $post->id))
            ->assertOk()
            ->assertJsonCount($commentCount)
            ->assertJsonStructure([
                [
                    'id',
                    'text',
                    'parent_id',
                    'post_id',
                    'user_id',
                ]
            ]);
    }

    /** @test */
    public function it_returns_correct_comments_from_post()
    {
        $post = Post::factory()->create();
        $comments = Comment::factory()->count(5)->create([
            'post_id' => $post->id,
        ]);
        $response = $this->getJson(route('api.comments.root', $post->id))
            ->assertOk();

        $comments->each(function($comment) use($response) {
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
    public function it_allows_non_auth_users_to_fetch_comments()
    {
        $post = Post::factory()->create();
        $this->getJson(route('api.comments.root', $post->id))
            ->assertOk();
    }

    /** @test */
    public function it_allows_auth_admins_to_fetch_comments()
    {
        $this->loginAdmin();
        $post = Post::factory()->create();
        $this->getJson(route('api.comments.root', $post->id))
            ->assertOk();
    }

    /** @test */
    public function it_allows_auth_users_to_fetch_comments()
    {
        $this->loginUser();
        $post = Post::factory()->create();
        $this->getJson(route('api.comments.root', $post->id))
            ->assertOk();
    }

    /** @test */
    public function it_returns_correct_comments_of_a_comment()
    {
        $parentComment = Comment::factory()->create();
        $nestedComment = Comment::factory()->create([
            'post_id' => null,
            'parent_id' => $parentComment->id,
        ]);
        $this->getJson(route('api.comments.nested', $parentComment->id))
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'id' => $nestedComment->id,
                'text' => $nestedComment->text,
                'parent_id' => $nestedComment->parent_id,
                'post_id' => $nestedComment->post_id,
                'user_id' => $nestedComment->user_id,
            ]);
    }

}
