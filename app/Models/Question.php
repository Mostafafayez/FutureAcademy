<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'question_text',
        'answer_type',
        'options',
        'correct_answer',
    ];
    public $timestamps =false;

    // Define the inverse relationship with Lesson
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
