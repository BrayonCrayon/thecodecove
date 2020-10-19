<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    /**
     * Handle the comment "deleted" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        if ($comment->comments()->count() > 0) {
            $comment->comments()->delete();
        }
    }
}
