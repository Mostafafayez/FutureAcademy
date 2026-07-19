<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'video_url',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(teacher::class);
    }
}
