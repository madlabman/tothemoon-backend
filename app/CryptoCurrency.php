<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class CryptoCurrency extends NeoEloquent
{
    protected $label = 'CryptoCurrency';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'symbol',
        'market_id',
        'stored_price',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'stored_price'  => 0,
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
}