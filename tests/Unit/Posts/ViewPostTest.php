<?php

namespace Tests\Unit\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Utility;

class ViewPostTest extends TestCase
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
    public function it_brings_back_existing_post()
    {
        $post = Post::all()->random()->first();
        $data = $this->getJson(route('api.posts.view', [
            'post' => $post->id,
        ]))
            ->assertOk()
            ->getOriginalContent();

        $this->assertEquals($data, $post, "Requested Post does not equal post in database");
    }

    /** @test */
    public function it_does_not_allow_non_existing_post()
    {
        $nonExistingPostId = Post::orderByDesc('id')->first()->id + 1;
        $this->getJson(route('api.posts.view', [
            'post' => $nonExistingPostId,
        ]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
