<?php

namespace Tests\Unit\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Utility;

class FetchPostsTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;

    private $utility;

    /** @test */
    public function it_brings_back_all_published_posts()
    {
        $posts = Post::published()->get();

        $response = $this->getJson(route('api.posts'))
            ->assertOk();

        $posts->each(function ($item) use ($response) {
            $response->assertJsonFragment([
                'id'           => $item->id,
                'name'         => $item->name,
                'content'      => $item->content,
                'published_at' => $item->published_at,
                'status_id'    => $item->status_id,
                'created_at'   => $item->created_at,
            ]);
        });
        $this->assertEquals($posts->count(), $response->getOriginalContent()->count(), "Brought back more posts than expected.");
    }

    /** @test */
    public function it_brings_back_all_comments_for_all_published_posts()
    {
        $response = $this->getJson(route('api.posts'))
            ->assertOk();

        $response->getOriginalContent()->each(function ($item) use ($response) {
            $this->assertDatabaseHas('posts', [
                'id' => $item->id,
                'name' => $item->name,
                'content' => $item->content,
                'user_id' => $item->user_id,
                'status_id' => $item->status_id,
                'published_at' => $item->published_at,
            ]);
            $this->assertNotEmpty($item['comments']);
        });

        $comments = $response->getOriginalContent()->pluck('comments')->flatten();
        $comments->each(function ($item) {
            $this->assertDatabaseHas('comments', [
                'id' => $item->id,
                'post_id' => $item->post_id,
                'parent_id' => $item->parent_id,
                'user_id' => $item->user_id,
                'text' => $item->text
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
