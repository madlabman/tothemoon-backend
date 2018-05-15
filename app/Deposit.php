<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Deposit extends NeoEloquent
{
    /**
     * Status constants.
     */
    public const DEPOSIT_WAIT = 1;
    public const DEPOSIT_ACTIVE = 2;
    public const DEPOSIT_CLOSED = 4;

    protected $label = 'Deposit';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'initial_amount',   // начальный баланс
        'invested_at',      // дата открытия
        'duration',         // срок инвестирования
        'status',           // статус вклада
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'invested_at'   => null,
        'status'        => self::DEPOSIT_WAIT,
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
        return $this->belongsTo(User::class, 'INVESTED');
    }

    /**
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\HasOne
     */
    public function balance()
    {
        return $this->hasOne(Balance::class, 'HAS_BALANCE');
    }

    public function payments()
    {
        return $this->hasMany(DepositPayment::class, 'PAY');
    }
}