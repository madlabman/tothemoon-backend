<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Fund extends NeoEloquent
{
    protected $label = 'Fund';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'balance',
        'token_count',
        'token_price',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'balance'       => 0,
        'token_count'   => 0,
        'token_price'   => 0,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /*
     *
     * Relations.
     *
     */
}