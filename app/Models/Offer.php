<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

protected $fillable = [
    'educational_level_id',
    'teacher_id',
    'title',
    'description',
    'discount_percentage',
];

public function teacher()
{
    return $this->belongsTo(teacher::class);
}
    // العلاقة مع EducationalLevel
    public function educationalLevel()
    {
        return $this->belongsTo(EducationalLevel::class);
    }
}
