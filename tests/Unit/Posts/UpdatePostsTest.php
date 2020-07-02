<?php

namespace Tests\Unit\Posts;

use App\Models\Post;
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
        Sanctum::actingAs(
            $this->utility->user,
            ['*']
        );

        $post = Post::all()->random()->first();
        $name = $this->faker->name . self::ALTERED_STRING;
        $content = $this->faker->text . self::ALTERED_STRING;

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $name,
            'content' => '',
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => '',
            'content' => $content,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing('posts', [
           'name' => $name,
           'content' => $content,
        ]);
        $this->assertDatabaseHas('posts', [
            'name' => $post->name,
            'content' => $post->content,
            'id' => $post->id,
        ]);
    }

    /** @test */
    public function it_does_not_allow_undefined_parameters()
    {
        Sanctum::actingAs(
            $this->utility->user,
            ['*']
        );

        $post = Post::all()->random()->first();

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => null,
            'content' => null,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);


        $this->assertDatabaseHas('posts', [
            'name' => $post->name,
            'content' => $post->content,
            'id' => $post->id,
        ]);
    }

    /** @test */
    public function it_allows_post_to_be_updated()
    {
        Sanctum::actingAs(
            $this->utility->user,
            ['*']
        );

        $post = Post::all()->random()->first();
        $name = $this->faker->name . self::ALTERED_STRING;
        $content = $this->faker->text . self::ALTERED_STRING;

        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $name,
            'content' => $content,
        ])
            ->assertOk();

        $this->assertDatabaseHas('posts', [
           'id' => $post->id,
           'name' => $name,
           'content' => $content,
        ]);
    }
}
