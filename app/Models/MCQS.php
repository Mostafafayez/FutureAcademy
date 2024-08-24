<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCQS extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'MCQS';
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
        'packages_id',
    ];

    /**
     * Get the lesson that owns the link.
     */



     public function packages()
     {
         return $this->belongsTo(packages::class);
     }
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
