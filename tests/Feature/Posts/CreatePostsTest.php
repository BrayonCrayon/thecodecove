<?php

namespace Tests\Feature\Posts;

use App\Models\Status;
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
    public function it_does_not_allow_non_auth_users()
    {
        $this->postJson(route('api.posts.store'), [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
            'userId' => $this->utility->user->id,
        ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function it_does_not_allow_guest_to_create_post()
    {
        $this->utility->loginGuest();
        $this->postJson(route('api.posts.store'), [
            'name' => $this->faker->name,
            'content' => $this->faker->text,
            'userId' => $this->utility->user->id,
        ])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_does_allow_auth_users()
    {
        $this->utility->loginAdmin();

        $name = $this->faker->name;
        $content = $this->faker->text;
        $this->postJson(route('api.posts.store'), [
                'name' => $name,
                'content' => $content,
                'userId' => $this->utility->user->id,
            ])
            ->assertOk()
            ->assertJsonStructure([
                'name',
                'content',
                'user_id',
                'status_id',
                'published_at',
                'created_at',
            ])
            ->assertJson([
                'name' => $name,
                'content' => $content,
                'status_id' => Status::DRAFT,
                'published_at' => null,
                'user_id' => $this->utility->user->id,
            ]);
    }
}
