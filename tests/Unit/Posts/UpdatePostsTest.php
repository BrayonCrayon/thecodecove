<?php

namespace Tests\Unit\Posts;

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
    const ALTERED_STRING = 'ALTERED_STRING';

    protected function setUp(): void
    {
        parent::setUp();
        $this->utility = new Utility($this);
        $this->utility->testSetup();
    }

    /** @test */
    public function it_does_not_allow_empty_parameters()
    {
        $this->utility->loginAdmin();
        $post = Post::all()->random()->first();
        $name = $this->faker->name . self::ALTERED_STRING;
        $content = $this->faker->text . self::ALTERED_STRING;
        $status = $post->status_id === Status::DRAFT ? Status::PUBLISHED : Status::DRAFT;

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $name,
            'content' => '',
            'status_id' => $status,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => '',
            'content' => $content,
            'status_id' => $status,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $name,
            'content' => $content,
            'status_id' => '',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing('posts', [
           'name' => $name,
           'content' => $content,
           'status_id' => $status
        ]);
        $this->assertDatabaseHas('posts', [
            'name' => $post->name,
            'content' => $post->content,
            'id' => $post->id,
            'status_id' => $post->status_id,
        ]);
    }

    /** @test */
    public function it_does_not_allow_undefined_parameters()
    {
        $this->utility->loginAdmin();

        $post = Post::all()->random()->first();

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => null,
            'content' => null,
            'status_id' => null,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);


        $this->assertDatabaseHas('posts', [
            'name' => $post->name,
            'content' => $post->content,
            'status_id' => $post->status_id,
            'id' => $post->id,
        ]);
    }

    /** @test */
    public function it_allows_post_to_be_updated()
    {
        $this->utility->loginAdmin();

        $post = Post::all()->random()->first();
        $name = $this->faker->name . self::ALTERED_STRING;
        $content = $this->faker->text . self::ALTERED_STRING;
        $status = $post->status_id === Status::DRAFT ? Status::PUBLISHED : Status::DRAFT;

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $name,
            'content' => $content,
            'status_id' => $status,
        ])
            ->assertOk();

        $this->assertDatabaseHas('posts', [
           'id' => $post->id,
           'name' => $name,
           'content' => $content,
           'status_id' => $status
        ]);
    }

    /** @test */
    public function it_resets_published_at_when_post_is_updated_to_draft()
    {
        $this->utility->loginAdmin();
        $post = Post::published()->get()->random()->first();

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $post->name,
            'content' => $post->content,
            'status_id' => Status::DRAFT,
        ])
            ->assertOk();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'published_at' => null,
            'status_id' => Status::DRAFT,
        ]);
    }

    /** @test */
    public function it_sets_published_at_when_post_is_updated_to_published()
    {
        $this->utility->loginAdmin();
        $post = Post::drafted()->first();

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $post->name,
            'content' => $post->content,
            'status_id' => Status::PUBLISHED,
        ])
            ->assertOk();
        $publishedDate = now();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'name' => $post->name,
            'content' => $post->content,
            'published_at' => $publishedDate->toDateTimeString(),
            'status_id' => Status::PUBLISHED,
        ]);
    }
}
