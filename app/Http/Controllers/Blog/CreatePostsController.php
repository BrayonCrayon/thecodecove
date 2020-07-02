<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class CreatePostsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => 'string',
            'content' => 'string',
            'userId' => 'integer|exists:users,id',
        ]);

        $post = Post::create([
            'name' => $request->get('name'),
            'content' => $request->get('content'),
            'user_id' => $request->get('userId')
        ]);

        return response()->json($post);
    }
}
