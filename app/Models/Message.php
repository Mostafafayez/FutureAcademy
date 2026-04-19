<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{


    protected $table  = 'messages';
    public $timestamps = false;
    protected $fillable = ['message', 'user_id','teacher_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function teacher()
{
    return $this->belongsTo(Teacher::class);
}

}
