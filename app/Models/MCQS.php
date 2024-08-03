<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mcqs extends Model
{
    use HasFactory;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lesson_id',
        'url',
        'title',
        'description',
    ];

    /**
     * Get the lesson that owns the link.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
