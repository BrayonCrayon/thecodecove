<?php

namespace Tests\Feature\Statuses;

use App\Models\Status;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Utility;

class FetchStatusesTest extends TestCase
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
    public function it_brings_back_all_statuses() {
        $response = $this->getJson(route('api.statuses'))
            ->assertOk()
            ->assertJsonStructure([
                [
                    'id',
                    'name',
                ]
            ]);

        Status::all()->each(function ($status) use ($response) {
            $response->assertJsonFragment([
                'id' => $status->id,
                'name' => $status->name,
            ]);
        });
    }
}
