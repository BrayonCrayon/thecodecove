<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $fillable = [
        'name',
        'content',
        'user_id',
        'status_id',
        'published_at',
        'created_at',
    ];
    protected $with = [
        'user',
        'status',
    ];

    /*
     *********************************************************
     *** Relationships
     *********************************************************
     */

    /**
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function status() {
        return $this->belongsTo(Status::class);
    }

    /**
     * @return HasMany
     */
    public function comments() {
        return $this->hasMany(Comment::class)->orderByDesc('created_at');
    }



    /*
     *********************************************************
     *** Scope Queries
     *********************************************************
     */

    /**
     * @param $query
     * @param string $order
     * @return mixed
     */
    public function scopeSortByCreatedAt($query, $order = 'desc')
    {
        return $query->orderBy('created_at', $order);
    }

    /**
     * @param $query
     * @param string $order
     * @return mixed
     */
    public function scopeOrderByPublished($query, $order = 'desc')
    {
        return $query->orderBy('published_at', $order);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('status_id', Status::PUBLISHED);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDrafted($query)
    {
        return $query->whereNull('published_at')
            ->where('status_id', Status::DRAFT);
    }

}
