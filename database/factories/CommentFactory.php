<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    $isRootComment = $faker->randomElement([1, 0]) > 0;
    return [
        'text' => $faker->text,
        // Factory a comment here and dont run queries in the factories unless absolutely necessary as it can get very slow
        'post_id' => $isRootComment || Comment::all()->count() === 0 ? Post::all()->random()->id : null,
        'user_id' => User::all()->first()->id,
        'parent_id' => !$isRootComment && Comment::all()->count() > 0 ? Comment::all()->random()->id : null,
    ];
});
