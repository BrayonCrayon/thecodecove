<?php

namespace App\Http\Controllers\Post;

use App\Helpers\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
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
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return JsonResponse|object|void
     */
    public function __invoke(UpdatePostRequest $request, Post $post)
    {
        Gate::authorize('is-admin');
        $post->update($request->validated());
        return response()->json('success');
    }
}
