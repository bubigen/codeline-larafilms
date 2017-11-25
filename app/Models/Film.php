<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\Services\SlugService;

class Film extends Model
{
	use Sluggable;

    /**
     * Mass assignable
     *
     * @return array
     */
    protected $fillable = ['name', 'description', 'rating', 'ticket_price', 'release_date', 'country', 'photo', 'slug'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Relationship: comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Always provide full url
     */
    public function getPhotoAttribute($value) {
        return Storage::disk('public')->url($value);
    }
}
