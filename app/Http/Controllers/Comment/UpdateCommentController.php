<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class UpdateCommentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param UpdateCommentRequest $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function __invoke(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());
        return response()->json($comment);
    }
}
