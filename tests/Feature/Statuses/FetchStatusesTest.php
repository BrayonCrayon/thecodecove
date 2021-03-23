<?php

namespace Tests\Feature\Statuses;

use App\Models\Status;
use Tests\TestCase;

class FetchStatusesTest extends TestCase
{
    /** @test */
    public function it_returns_data_in_expected_shape() {
        $this->getJson(route('api.statuses'))
            ->assertOk()
            ->assertJsonStructure([
                [
                    'id',
                    'name',
                ]
            ]);
    }

    /** @test */
    public function it_brings_back_all_statuses()
    {
        $response = $this->getJson(route('api.statuses'));
        Status::all()->each(function ($status) use ($response) {
            $response->assertJsonFragment([
                'id' => $status->id,
                'name' => $status->name,
            ]);
        });
    }
}
