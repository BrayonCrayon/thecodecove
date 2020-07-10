<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeletePostsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request, Post $post)
    {
        $post->delete();
        return response()->json('success');
    }
}
