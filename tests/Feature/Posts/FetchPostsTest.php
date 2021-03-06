<?php

namespace Tests\Feature\Posts;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Utility;

class FetchPostsTest extends TestCase
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
    public function it_allows_non_users_to_access_posts()
    {
        $this->getJson(route('api.posts'))
            ->assertOk()
            ->assertJsonStructure([
                [
                    'id',
                    'name',
                    'content',
                    'user_id',
                    'status_id',
                    'published_at',
                    'created_at',
                    'comments' => [
                        [
                            'id',
                            'post_id',
                            'parent_id',
                            'user_id',
                            'text'
                        ]
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_allows_users_to_access_posts()
    {
        $this->utility->loginAdmin();
        $this->getJson(route('api.posts'))
            ->assertOk()
            ->assertJsonStructure([
                [
                    'id',
                    'name',
                    'content',
                    'user_id',
                    'status_id',
                    'published_at',
                    'created_at',
                    'comments' => [
                        [
                            'id',
                            'post_id',
                            'parent_id',
                            'user_id',
                            'text'
                        ]
                    ]
                ]
            ]);
    }

}
