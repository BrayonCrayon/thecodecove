<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FetchDraftedPostsController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        Gate::authorize('is-admin');
        $draftedPosts = Post::drafted()->paginate(25);
        return response()->json(new PostCollection($draftedPosts));
    }
}
