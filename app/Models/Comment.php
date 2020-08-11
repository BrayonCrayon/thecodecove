<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    /*
     *********************************************************
     *** Relationships
     *********************************************************
     */


    /**
     * @return HasMany
     */
    public function comments() {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }
}
