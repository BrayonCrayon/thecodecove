<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Status;
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
            'content' => 'required|string',
            'status_id' => 'required|exists:statuses,id',
        ]);

        $post->update($request->all());

        if ($request->get('status_id') === Status::PUBLISHED) {
            $post->update([
                'published_at' => now(),
            ]);
        }

        return response()->json('success');
    }
}
