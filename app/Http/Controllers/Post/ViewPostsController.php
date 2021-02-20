<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ViewPostsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     */
    public function __invoke(Request $request, Post $post)
    {
        $post->load('comments');
        return response()->json($post);
    }
}
