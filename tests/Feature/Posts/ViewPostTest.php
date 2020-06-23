<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
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
    public function it_allows_users_to_access_post()
    {
        $post = Post::all()->random();
        $this->actingAs($this->utility->user)
            ->getJson(`api/posts/{$post->id}`)
            ->assertOk()
            ->assertJsonStructure([
                'name',
                'content',
                'user_id',
                'created_at',
            ]);
    }

    /** @test */
    public function it_allows_non_users_to_access_post()
    {
        $post = Post::all()->random();
        $this->getJson(`api/posts/{$post->id}`)
            ->assertOk()
            ->assertJsonStructure([
                'name',
                'content',
                'user_id',
                'created_at',
            ]);
    }
}
