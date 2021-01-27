<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\Status;

class PostObserver
{
    public function updating(Post $post)
    {
        if ($post->status_id === Status::PUBLISHED && is_null($post->published_at)) {
            $post->published_at = now();
        }

        if ($post->status_id === Status::DRAFT && !is_null($post->published_at)) {
            $post->published_at = null;
        }
    }
}
