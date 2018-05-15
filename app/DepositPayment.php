<?php

namespace App;

class DepositPayment extends Payment
{
    protected $label = 'DepositPayment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'wallet',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'is_confirmed'  => false,
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
    public function deposit()
    {
        return $this->belongsTo(Deposit::class, 'PAY');
    }
}