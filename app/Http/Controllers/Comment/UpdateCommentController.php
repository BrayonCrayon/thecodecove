<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateCommentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function __invoke(Request $request, Comment $comment)
    {
        $request->validate([
            'text' => 'required|string',
        ]);
        $comment->update($request->all());
        return response()->json('success');
    }
}
