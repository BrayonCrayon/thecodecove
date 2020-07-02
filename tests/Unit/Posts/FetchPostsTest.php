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

    protected function setUp(): void
    {
        parent::setUp();
        $this->utility = new Utility($this);
        $this->utility->testSetup();
    }

    /** @test */
    public function it_brings_back_all_posts()
    {
        $posts = Post::all();

        $response = $this->getJson(route('api.posts'))
            ->assertOk();

        $posts->each(function($item) use ($response) {
            $response->assertJsonFragment([
                'id' => $item->id,
                'name' => $item->name,
                'content' => $item->content,
                'created_at' => $item->created_at,
            ]);
        });
    }
}
