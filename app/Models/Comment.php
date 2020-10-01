<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'text',
        'post_id',
        'user_id',
        'parent_id',
    ];
    protected $guarded = [];
    protected $with = [
        'user'
    ];

    /*
     *********************************************************
     *** Scope Queries
     *********************************************************
     */

    /**
     * @param $query
     * @return mixed
     */
    public function scopeIsRootComment($query) {
        return $query->whereNotNull('post_id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeIsNestedComment($query) {
        return $query->whereNull('post_id');
    }

    /**
     * @param $query
     * @param $parentId
     * @return mixed
     */
    public function scopeParentIs($query, $parentId) {
        return $query->where('parent_id', $parentId);
    }

    /**
     * @param $query
     * @param $postId
     * @return mixed
     */
    public function scopePostIs($query, $postId) {
        return $query->where('post_id', $postId);
    }

    /*
     *********************************************************
     *** Relationships
     *********************************************************
     */

    /**
     * @return HasMany
     */
    public function comments() {
        return $this->hasMany(Comment::class, 'parent_id', 'id')->orderByDesc('created_at');
    }

    /**
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function post() {
        return $this->belongsTo(Post::class);
    }
}
