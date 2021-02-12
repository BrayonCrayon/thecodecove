<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeleteCommentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Comment $comment
     * @return JsonResponse
     * @throws \Exception
     */
    public function __invoke(Request $request, Comment $comment)
    {
        $comment->delete();
        return response()->json("success");
    }
}
