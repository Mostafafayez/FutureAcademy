
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    protected $fillable = [

        'user_id',

        'daily_room_name',
        'daily_room_url',

        'meeting_token',

        'status',

        'started_at',
        'ended_at',
    ];

    protected $casts = [

        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(users::class);
    }
}

