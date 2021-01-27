<?php

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::updateOrCreate(
            [
                'name' => 'draft',
            ]
        );
        Status::updateOrCreate(
            [
                'name' => 'published',
            ]
        );
    }
}
