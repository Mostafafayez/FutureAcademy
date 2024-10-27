<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalLevel extends Model
{
    use HasFactory;



    protected $table = 'educational_levels';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
    ];

    /**
     * Get the subjects for the educational level.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get the users for the educational level.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }


    public function lesson()
    {
        return $this->hasMany(Lesson::class);
    }


    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'eductional_level_teacher', 'educational_level_id', 'teacher_id');
    }

}
