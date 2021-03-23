<?php

namespace Tests\Unit\Posts;

use App\Models\Post;
use App\Models\Status;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PostModelTest extends TestCase
{
    /** @test */
    public function it_resets_published_at_when_post_is_updated_to_draft()
    {
        $post = factory(Post::class)->create();
        $this->loginAdmin();
        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $post->name,
            'content' => $post->content,
            'status_id' => Status::DRAFT,
        ])->assertOk();

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
        $this->loginAdmin();
        $post = factory(Post::class)->create([
            'status_id' => Status::DRAFT,
            'published_at' => null,
        ]);

        Carbon::setTestNow();
        $this->putJson(route('api.posts.update', $post->id), [
            'name' => $post->name,
            'content' => $post->content,
            'status_id' => Status::PUBLISHED,
        ])->assertOk();

        $this->assertDatabaseHas('posts', [
            'published_at' => now()->toDateTimeString(),
            'status_id' => Status::PUBLISHED,
        ]);
    }
}
