<?php

namespace App\Http\Controllers\Blog;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

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
        $validated = $request->validate([
            'name' => 'required|string',
            'content' => 'required|string',
            'status_id' => 'required|exists:statuses,id',
        ]);

        Gate::authorize('is-admin');

        $post->update($validated);

        return response()->json('success');
    }
}
