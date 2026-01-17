<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserVideoProgress extends Pivot
{
    protected $table = 'user_video_progress';

    protected $appends = ['status'];

    protected $casts = [
        'percentage' => 'integer',
    ];

    public function getStatusAttribute()
    {
        return match (true) {
            $this->percentage === 0 => 'not_started',
            $this->percentage >= 100 => 'completed',
            default => 'in_progress',
        };
    }
}
