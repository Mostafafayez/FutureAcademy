<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeacherBundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'educational_levels_id',
        'price',
        'status'
    ];

    public function educationalLevel()
    {
        return $this->belongsTo(EducationalLevel::class,'educational_levels_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(
            teacher::class,
            'teacher_bundle_teacher',
            'teacher_bundle_id',
            'teacher_id'
        );
    }
}
