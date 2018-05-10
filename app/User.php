<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;
use Vinelab\NeoEloquent\Eloquent\Relations\HasMany;

class User extends NeoEloquent implements
    AuthenticatableContract,
    AuthorizableContract,
    JWTSubject
{
    use Notifiable, Authenticatable, Authorizable;

    protected $label = ['User'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'name',
        'phone',
        'login',
        'password',
        'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'is_admin',
        'open_signal_access',
        'signal_access',
        'invest_level',
        'updated_at',
        'created_at',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'is_admin'              => false,
        'open_signal_access'    => false,
        'signal_access'         => 0,
        'invest_level'          => 0,
        'token_last_valid_time' => null,
    ];

    public function username()
    {
        return 'login';
    }

    /*
     *
     * Relations.
     *
     */

    /**
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\HasOne
     */
    public function balance()
    {
        return $this->hasOne(Balance::class, 'HAS_BALANCE');
    }

    /**
     * @return HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'PAY');
    }

    public function withdraws()
    {
        return $this->hasMany(Withdraw::class, 'WITHDRAW');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'HAS_REFERRAL');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'HAS_REFERRAL');
    }

    public function hasSignalAccess(int $level)
    {
        return $this->signal_access & $level;
    }

    public function signals()
    {
        $levels = [
            Signal::RED_LEVEL,
            Signal::YELLOW_LEVEL,
            Signal::GREEN_LEVEL,
        ];

        foreach ($levels as $level) {
            if (!$this->hasSignalAccess($level)) {
                unset($level);
            }
        }

        return Signal::whereIn('level', $levels)->where('created_at', '<', 's_a_expired_at')->get();
    }

    /*
     *
     * Implement JWTSubject
     *
     */

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


}
