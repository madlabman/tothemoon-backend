<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Payment extends NeoEloquent
{
    /*
    * Constants for payment type
    */
    public const BTC = 1;
    public const RUB = 2;

    protected $label = 'Payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'wallet',
        'type',
        'is_confirmed',
        'tx_hash',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'amount'        => 0.0,
        'is_confirmed'  => false,
        'tx_hash'       => false,   // null value ignored by Neo4j
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'PAY');
    }
}