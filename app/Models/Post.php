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
    public function user() {
        return $this->belongsTo(User::class);
    }


}
