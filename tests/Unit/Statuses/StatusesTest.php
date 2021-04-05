<?php

namespace Tests\Unit\Statuses;

use App\Models\Post;
use App\Models\Status;
use Tests\TestCase;

class StatusesTest extends TestCase
{
    /** @test */
    public function it_has_many_posts()
    {
        $expectedCount = 5;
        Post::factory()->count($expectedCount)->create();
        $status = Status::findOrFail(Status::PUBLISHED);
        $this->assertInstanceOf(Post::class, $status->posts->first());
        $this->assertEquals($expectedCount, $status->posts->count());
    }

}
