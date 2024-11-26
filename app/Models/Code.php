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
        'mac_address2',
        'code',
        'user_id',
        'lesson_id',
        'type2',
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate a random string with a specified length (e.g., 8 characters)
            $model->code = Str::random(25);
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(packages::class, 'lesson_id'); // Assuming 'lesson_id' is the foreign key
    }

}

