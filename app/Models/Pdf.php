<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    use HasFactory;


    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */




     protected $appends=['FullSrc'];
    protected $fillable = [
        'lesson_id',
        'pdf',
        'title',
        'description',
    ];

    /**
     * Get the lesson that owns the PDF.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }


    public function getFullSrcAttribute()  {
        return asset('storage/'.$this->pdf);

    }
}
