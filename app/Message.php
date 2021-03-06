<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Vinelab\NeoEloquent\Eloquent\Model as NeoEloquent;

class Message extends NeoEloquent
{
    protected $label = 'Message';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'sender',
    ];

    /**
     * Default values for fields.
     *
     * @var array
     */
    protected $attributes = [
        'is_read' => false
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
    ];

    public function getKey()
    {
        return $this->id;
    }

    /*
     *
     * Relations.
     *
     */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'SENT_MESSAGE');
    }

    /**
     * @return \Vinelab\NeoEloquent\Eloquent\Relations\HasOne
     */
    public function toUser()
    {
        return $this->hasOne(User::class, 'RECEIVED_MESSAGE');
    }
}