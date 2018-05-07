<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Signal extends NeoEloquent
{
    /*
     * Signals levels constants
     */
    public const RED_LEVEL = 1;
    public const YELLOW_LEVEL = 2;
    public const GREEN_LEVEL = 4;

    protected $label = 'Signal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'info',
        'level',
        'is_private',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'is_private' => false,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}