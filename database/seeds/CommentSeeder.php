<?php

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Root comments
        Post::all()->each(function($post) {
           factory(Comment::class, 4)->create([
               'post_id' => $post->id,
               'parent_id' => null,
           ]);
        });

        // Create Nested Comments
        Comment::all()->each(function ($comment) {
            factory(Comment::class, 2)->create([
                'post_id' => null,
                'parent_id' => $comment->id,
            ]);
        });
    }
}
