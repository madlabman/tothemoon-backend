<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Profit extends NeoEloquent
{
    protected $label = 'Profit';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token_change',
        'token_change_percent',
        'token_price',
        'balance',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'token_change'          => 0,
        'token_change_percent'  => 0,
        'token_price'           => 0,
        'balance'               => 0,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /*
     *
     * Relations.
     *
     */

    public function fund()
    {
        return $this->belongsTo(Fund::class, 'PROFIT');
    }
}