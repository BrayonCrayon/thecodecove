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
        /*
         * Might want to change this to deleting and delete the child comments before the parent
         */
        if ($comment->comments()->count() > 0) {
            $comment->comments()->delete();
        }
    }
}
