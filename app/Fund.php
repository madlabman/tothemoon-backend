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
        'balance_usd',
        'balance_btc',
        'token_count',
        'token_price',
        'manual_balance_usd',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'balance_usd'        => 0,
        'balance_btc'        => 0,
        'token_count'        => 0,
        'token_price'        => 0,
        'manual_balance_usd' => 0,
        'reserve_usd'        => 0,
        'capital_market'     => 0,  // Value of tokens on markets in USD
        'capital_blockchain' => 0,  // Value of tokens on blockchain in USD
        'capital_etherscan'  => 0,  // Value of tokens on etherscan in USD
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

    public function profits()
    {
        return $this->hasMany(Profit::class, 'PROFIT');
    }

    public function coins()
    {
        return $this->hasMany(Coin::class, 'HAS_COIN');
    }
}