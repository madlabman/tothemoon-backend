<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class FundBalanceHistory extends NeoEloquent
{
    protected $label = 'FundBalanceHistory';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

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
        'id',
        'created_at',
        'updated_at',
    ];

    /*
     *
     * Relations.
     *
     */

    public function previousEntry()
    {
        return $this->belongsTo(self::class, 'PREVIOUS_ENTRY');
    }
}