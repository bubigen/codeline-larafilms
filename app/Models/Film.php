<?php

namespace App\Models;

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
}
