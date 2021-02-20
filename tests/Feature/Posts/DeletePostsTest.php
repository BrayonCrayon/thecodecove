<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class DeletePostsTest extends TestCase
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
    public function it_does_not_allow_non_auth_users_to_delete_post()
    {
        $post = Post::first();
        $this->deleteJson(route('api.posts.delete', $post->id))
            ->assertUnauthorized();
    }

    /** @test */
    public function it_does_not_allow_guest_to_delete_post()
    {
        $this->utility->loginUser();
        $post = Post::first();
        $this->deleteJson(route('api.posts.delete', $post->id))
            ->assertNotFound();
    }

    /** @test */
    public function it_does_allow_auth_users_to_delete_post()
    {
        $this->utility->loginAdmin();
        $post = Post::first();

        $this->deleteJson(route('api.posts.delete', $post->id))
            ->assertOK();
    }
}
