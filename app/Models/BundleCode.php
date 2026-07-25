<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BundleCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_bundle_id',
        'user_id',
        'mac_address',
        // 'mac_address2',
        'expires_at',
        'status',
        'code'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            if (!$model->code) {
                $model->code = Str::random(10);
            }

        });
    }

    public function bundle()
    {
        return $this->belongsTo(TeacherBundle::class,'teacher_bundle_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
