<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Transaction extends NeoEloquent
{
    /*
     * Transaction type
     */
    public const PAYMENT = 'PAYMENT';
    public const WITHDRAW = 'WITHDRAW';
    public const ACCOUNT = 'ACCOUNT';
    public const REFERRAL = 'REFERRAL';
    public const DIVIDEND = 'DIVIDEND';

    protected $label = 'Transaction';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'token_count',
        'token_price',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'token_count' => 0,
        'token_price' => 0,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /*
     * Relations.
     */

    /**
     * Define user related to transaction.
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'OWNER');
    }
}