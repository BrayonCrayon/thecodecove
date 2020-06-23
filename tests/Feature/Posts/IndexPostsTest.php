<?php

namespace Tests\Feature\Posts;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Utility;

class IndexPostsTest extends TestCase
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
    public function it_does_allow_auth_users()
    {
        $this->actingAs($this->utility->user)
            ->get(route('posts.index'))
            ->assertOk()
            ->assertViewIs('posts.index');
    }

    /** @test */
    public function it_does_allow_non_auth_users()
    {
        $this->get(route('posts.index'))
            ->assertOk()
            ->assertViewIs('posts.index');
    }
}
