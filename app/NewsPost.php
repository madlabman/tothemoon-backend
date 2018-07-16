<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class NewsPost extends NeoEloquent
{
    protected $label = 'NewsPost';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
    ];
}