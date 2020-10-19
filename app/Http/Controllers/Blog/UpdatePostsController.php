<?php

namespace App\Http\Controllers\Blog;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdatePostsController extends Controller
{
    private $userHelper;

    public function __construct(UserHelper $userHelper)
    {
        $this->userHelper = $userHelper;
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse|object|void
     */
    public function __invoke(Request $request, Post $post)
    {
        $request->validate([
            'name' => 'required|string',
            'content' => 'required|string',
            'status_id' => 'required|exists:statuses,id',
        ]);

        if ($this->userHelper->isAuthUserGuest()) {
            return response()
                ->json(['error' => "Unauthorized to create Post."])
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        $post->update($request->all());

        if ($request->get('status_id') === Status::PUBLISHED) {
            $post->update([
                'published_at' => now(),
            ]);
        } else {
            $post->update([
                'published_at' => null,
                'status_id' => Status::DRAFT,
            ]);
        }

        return response()->json('success');
    }
}
