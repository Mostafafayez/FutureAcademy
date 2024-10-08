<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */



     const STATUS_APPROVAL = 'approval';
    protected $fillable = [
        'name',
        'phone',
        'password',
        'educational_level_id',
        'status'
    ];

    public $timestamps = false; //
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function scores()
    {
        return $this->hasMany(Score::class);
    }


    public function codes()
    {
        return $this->hasMany(Code::class);
    }


    public function messages()
    {
        return $this->hasMany(Message::class);
    }



    public function educationalLevel()
    {
        return $this->belongsTo(EducationalLevel::class);
    }


    public function isApproved()
    {
        return $this->status = self::STATUS_APPROVAL;
    }


}
