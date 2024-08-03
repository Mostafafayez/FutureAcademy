<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'educational_level_id',
        'teacher_id',
        'type'
    ];


    public $timestamps = false;

    /**
     * Get the lessons for the subject.
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }


    public function educationalLevel()
    {
        return $this->belongsTo(EducationalLevel::class);
    }
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
