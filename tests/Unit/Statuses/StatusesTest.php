<?php

namespace Tests\Unit\Statuses;

use App\Models\Post;
use App\Models\Status;
use Tests\TestCase;

class StatusesTest extends TestCase
{
    /** @test */
    public function published_status_has_many_published_posts()
    {
        $createdCount = factory(Post::class, 5)->create()->count();
        $publishedPostCount = Status::published()->first()->posts()->count();
        $this->assertEquals($createdCount, $publishedPostCount);
    }

    /** @test */
    public function drafted_status_has_many_drafted_posts()
    {
        $createdCount = factory(Post::class, 5)->create([
            'status_id' => Status::DRAFT,
            'published_at' => null,
        ])->count();
        $draftedPostCount = Status::draft()->first()->posts()->count();
        $this->assertEquals($createdCount, $draftedPostCount);
    }

}
