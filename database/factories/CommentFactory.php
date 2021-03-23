<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'text' => $faker->text,
        'post_id' => function() { return factory(Post::class)->create()->id; },
        'user_id' => factory(User::class)->create()->id,
        'parent_id' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
