<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        Gate::authorize('is-admin');
        $post->delete();
        return response()->json('success');
    }
}
