<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class UpdatePostsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Post $post
     * @return void
     */
    public function __invoke(Request $request, Post $post)
    {
        $request->validate([
            'name' => 'required|string',
            'content' => 'required|string'
        ]);
        $post->update($request->all());
        return response()->json('success');
    }
}
