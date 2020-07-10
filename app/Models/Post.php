<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'name',
        'content',
        'user_id',
        'created_at',
    ];
    protected $with = [
        'user',
    ];

    /*
     *********************************************************
     *** Relationships
     *********************************************************
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
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

}
