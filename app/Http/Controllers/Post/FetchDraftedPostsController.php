<?php

namespace App\Http\Controllers\Post;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FetchDraftedPostsController extends Controller
{
    private $userHelper;

    public function __construct(UserHelper $userHelper)
    {
        $this->userHelper = $userHelper;
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request)
    {
        Gate::authorize('is-admin');

        $draftedPosts = Post::drafted()->get();
        return response()->json($draftedPosts);
    }
}
