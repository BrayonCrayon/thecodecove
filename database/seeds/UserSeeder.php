<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(config('app.developers')) // Put the empty array default value in the config file
            ->each(function ($developer) {
            factory(User::class)->create([
                'name' => $developer['name'],
                'email'=> $developer['email'],
            ]);
        });
    }
}
