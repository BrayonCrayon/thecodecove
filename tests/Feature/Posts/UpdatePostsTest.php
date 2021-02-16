<?php

namespace Tests\Feature\Posts;

use App\Models\Post;
use App\Models\Status;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class UpdatePostsTest extends TestCase
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
    public function it_does_not_allow_non_auth_users()
    {
        $post = Post::first();
        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
            'status_id' => $this->faker->randomElement([Status::DRAFT, Status::PUBLISHED]),
        ])->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_does_not_allow_guest_to_update_post()
    {
        $this->utility->loginUser();
        $post = Post::first();
        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
            'status_id' => $this->faker->randomElement([Status::DRAFT, Status::PUBLISHED]),
        ])->assertNotFound();
    }

    /** @test */
    public function it_does_allow_auth_user()
    {
        $this->utility->loginAdmin();
        $post = Post::first();
        $post->name = $this->faker->name . "-test";
        $post->content = $this->faker->text . "-test";
        $post->status_id = $post->status_id === Status::DRAFT ? Status::PUBLISHED : Status::DRAFT;
        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $post->name,
            'content' => $post->content,
            'status_id' => $post->status_id,
        ])->assertOk()
        ->assertJsonFragment([
            'name' => $post->name,
            'content' => $post->content,
            'status_id' => $post->status_id
        ]);
    }
}
