<?php

namespace App\Http\Controllers\Blog;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeletePostsController extends Controller
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
     * @param Post $post
     * @return JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request, Post $post)
    {
        if ($this->userHelper->isAuthUserGuest()) {
            return response()
                ->json(['error' => "Unauthorized to delete Post."])
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        $post->delete();
        return response()->json('success');
    }
}
