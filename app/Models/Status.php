<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    protected $fillable = [];
    protected $guarded = [];

    const DRAFT = 1;
    const PUBLISHED = 2;

    /*
     *********************************************************
     *** Relationships
     *********************************************************
     */

    /**
     * @return HasMany
     */
    public function posts() {
        return $this->hasMany(Post::class);
    }

}
