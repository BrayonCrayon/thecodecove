<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'name'         => $this->faker->name,
            'content'      => $this->faker->text,
            'user_id'      => User::factory(),
            'published_at' => now()->subMonths($this->faker->randomDigit),
            'status_id'    => Status::PUBLISHED,
        ];
    }

    public function drafted()
    {
        return $this->state(function (array $attributes) {
            return [
                'status_id'    => Status::DRAFT,
                'published_at' => null
            ];
        });
    }
}
