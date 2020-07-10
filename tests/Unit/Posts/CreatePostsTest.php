<?php

namespace Tests\Unit\Posts;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utility;

class CreatePostsTest extends TestCase
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
    public function it_does_not_allow_empty_parameters()
    {
        Sanctum::actingAs(
            $this->utility->user,
            ['*']
        );

        $name = $this->faker->name;
        $content = $this->faker->text;
        $this->postJson(route('api.posts.store'), [
            'name' => '',
            'content' => $content,
            'userId' => $this->utility->user->id,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->postJson(route('api.posts.store'), [
            'name' => $name,
            'content' => '',
            'userId' => $this->utility->user->id,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing('posts', [
            'name' => $name,
            'content' => $content,
            'user_id' => $this->utility->user->id,
        ]);
    }

    /** @test */
    public function it_does_not_allow_non_existing_user()
    {
        Sanctum::actingAs(
            $this->utility->user,
            ['*']
        );

        $name = $this->faker->name;
        $content = $this->faker->text;
        $nonExistingUserId = User::orderByDesc('id')->first()->id + 1;
        $this->postJson(route('api.posts.store'), [
            'name' => $name,
            'content' => $content,
            'userId' => $nonExistingUserId,
        ])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertDatabaseMissing('posts', [
            'name' => $name,
            'content' => $content,
            'user_id' => $nonExistingUserId,
        ]);
    }

    /** @test */
    public function it_persists_valid_post()
    {
        Sanctum::actingAs(
            $this->utility->user,
            ['*']
        );

        $data = $this->postJson(route('api.posts.store'), [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
            'userId' => $this->utility->user->id,
        ])->assertOk()
        ->getOriginalContent();

        $this->assertDatabaseHas('posts', [
            'name' => $data->name,
            'content' => $data->content,
            'user_id' => $data->user_id,
            'status_id' => Status::DRAFT,
            'published_at' => null,
            'created_at' => $data->created_at,
        ]);
    }
}
