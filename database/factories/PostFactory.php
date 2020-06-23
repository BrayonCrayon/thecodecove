<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'content' => $faker->text,
        'user_id' => User::get()->first()->id,
        'created_at' => $faker->dateTimeBetween('-5 months', 'now'),
    ];
});
