<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Tag extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class);
    }
}
