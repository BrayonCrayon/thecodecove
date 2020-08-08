<?php

namespace Tests\Unit\Statuses;

use App\Models\Post;
use App\Models\Status;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Utility;

class StatusesTest extends TestCase
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
    public function it_brings_back_all_statuses()
    {
        $statuses = Status::all();
        $this->assertEquals(collect([Status::DRAFT, Status::PUBLISHED])->count(), $statuses->count(), "Did not bring back all statuses");
        $this->assertTrue($statuses->contains('name','draft'), "Did not bring back draft status");
        $this->assertTrue($statuses->contains('name','published'), "Did not bring back published status");
    }

    /** @test */
    public function relationship_posts_brings_back_all_posts()
    {
        $postCount = Post::all()->count();
        $totalPostCountByStatus = 0;
        Status::all()->each(function($item) use (&$totalPostCountByStatus) {
            $totalPostCountByStatus += $item->posts->count();
        });
        $this->assertEquals($totalPostCountByStatus, $postCount, "Status Post relationship doesn't work properly.");
    }

}
