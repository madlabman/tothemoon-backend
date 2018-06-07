<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Coin extends NeoEloquent
{
    protected $label = 'Coin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sym',
        'amount',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'amount'    => 0.0,
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fund()
    {
        return $this->belongsTo(Fund::class, 'HAS_COIN');
    }
}