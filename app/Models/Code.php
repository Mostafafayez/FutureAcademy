<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'mac_address',
        'expires_at',
        'access_type',
        'code',
        'user_id',
        'teacher_name'
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->code = Str::uuid(); // Generating a unique code using UUID
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

