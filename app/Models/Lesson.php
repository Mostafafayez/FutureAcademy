<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subject_id',
        'teacher_id',
        'title',
        'description',
    ];
    public $timestamps = false;
    /**
     * Get the subject that owns the lesson.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the videos for the lesson.
     */
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Get the PDFs for the lesson.
     */
    public function pdfs()
    {
        return $this->hasMany(Pdf::class);
    }

    /**
     * Get the links for the lesson.
     */
    public function MCQ()
    {
        return $this->hasMany(MCQS::class);
    }



    public function teacher()
    {
        return $this->belongsTo(teacher::class, 'teacher_id');
    }

}
