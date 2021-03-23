<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreCommentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param StoreCommentRequest $request
     * @return JsonResponse
     */
    public function __invoke(StoreCommentRequest $request)
    {
        $comment = Comment::create($request->validated());
        $comment->load(['user']);
        return response()->json(CommentResource::make($comment));
    }
}
