<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comment;
use App\Models\Post;
use App\Models\Status;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'name'         => $faker->name,
        'content'      => $faker->text,
        'user_id'      => function () {
            return factory(User::class)->create()->id;
        },
        'published_at' => Carbon::now()->subMonths($faker->randomDigit),
        'status_id'    => Status::PUBLISHED,
    ];
});
