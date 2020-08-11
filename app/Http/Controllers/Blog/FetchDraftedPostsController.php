<?php

namespace App\Http\Controllers\Blog;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        if ($this->userHelper->isAuthUserGuest()) {
            return response()
                ->json(['error' => "Unauthorized to fetch drafted Posts."])
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        $draftedPosts = Post::drafted()->get();
        return response()->json($draftedPosts);
    }
}
