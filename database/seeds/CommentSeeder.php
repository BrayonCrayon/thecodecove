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
        $comments = collect();
        // Create Root comments
        Post::all()->each(function($post) use ($comments) {
           $comments->push(factory(Comment::class, 10)->make([
               'post_id' => $post->id,
               'parent_id' => null,
           ]));
        });

        Comment::insert($comments->flatten()->toArray());
        $comments = Comment::all();

        // Create Nested Comments
        $nestedComments = collect();
        $comments->each(function ($comment) use ($nestedComments) {
            $nestedComments->push(factory(Comment::class, 2)->make([
                'post_id' => null,
                'parent_id' => $comment->id,
            ]));
        });

        Comment::insert($nestedComments->flatten()->toArray());
    }
}
