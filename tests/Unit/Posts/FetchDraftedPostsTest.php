<?php

namespace Tests\Unit\Posts;

use App\Models\Status;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class FetchDraftedPostsTest extends TestCase
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
    public function it_does_return_all_drafted_posts()
    {
        $this->utility->loginAdmin();
        $posts = $this->getJson(route('api.posts.drafted'))
            ->assertOk()
            ->getOriginalContent();

        $posts->each(function ($post) {
           $this->assertDatabaseHas('posts', [
               'id' => $post->id,
               'name' => $post->name,
               'content' => $post->content,
               'status_id' => Status::DRAFT,
               'published_at' => $post->published_at,
           ]);
        });
    }
}
