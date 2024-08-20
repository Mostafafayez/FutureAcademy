<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class packages extends Model
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
     * Get the subject that owns the package.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the videos for the lesson.
     */


    public function educationalLevel()
    {
        return $this->belongsTo(EducationalLevel::class);
    }

    public function teacher()
    {
        return $this->belongsTo(teacher::class, 'teacher_id');
    }



    public function codes()
{
    return $this->hasMany(Code::class);
}
public function lessons()
{
    return $this->hasMany(Lesson::class);
}
}
