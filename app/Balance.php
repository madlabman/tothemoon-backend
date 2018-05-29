<?php

namespace App;

use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Balance extends NeoEloquent
{
    protected $label = 'Balance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'bonus',
        'token',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'body'  => 0.0,
        'bonus' => 0.0,
        'token' => 0.0,
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
    public function user()
    {
        return $this->belongsTo(User::class, 'HAS_BALANCE');
    }

    public function deposit()
    {
        return $this->belongsTo(Deposit::class, 'HAS_BALANCE');
    }
}