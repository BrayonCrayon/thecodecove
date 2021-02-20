<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use App\Models\Status;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    $status = $faker->randomElement([Status::DRAFT, Status::PUBLISHED]);
    return [
        'name' => $faker->name,
        'content' => $faker->text,
        'user_id' => User::get()->first()->id, // factory
        'published_at' => $status === Status::PUBLISHED ? $faker->dateTimeBetween('-5 months', 'now') : null,
        'status_id' => $status,
    ];
});
