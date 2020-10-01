<?php

namespace App\Http\Controllers\Blog;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CreatePostsController extends Controller
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
     * @return JsonResponse|Response|object
     */
    public function __invoke(Request $request)
    {
        if ($this->userHelper->isAuthUserGuest()) {
            return response()
                ->json(['error' => "Unauthorized to create Post."])
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        $request->validate([
            'name' => 'string',
            'content' => 'string',
            'userId' => 'integer|exists:users,id',
        ]);

        $post = Post::create([
            'name' => $request->get('name'),
            'content' => $request->get('content'),
            'status_id' => Status::DRAFT,
            'published_at' => null,
            'user_id' => $request->get('userId')
        ]);

        return response()->json($post);
    }
}
