<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['title', 'description', 'package_id','description_assistant','teacher_id','image_id'];

    public function package()
    {
        return $this->belongsTo(packages::class);
    }
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function teacher()
    {
        return $this->belongsTo(teacher::class);
    }


    public function image()
    {
        return $this->belongsTo(Image::class);
    }



    public function score()
    {
        return $this->hasOne(Score::class);
    }
    /**
     * Get the PDFs for the lesson.
     */
    public function pdfs()
    {
        return $this->hasMany(pdfs::class);
    }

    /**
     * Get the links for the lesson.
     */
    public function MCQ()
    {
        return $this->hasMany(MCQS::class);
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }


    public function users()
{
    return $this->belongsToMany(User::class, 'user_video_progress')
        ->using(UserVideoProgress::class)
        ->withPivot(['percentage'])
        ->withTimestamps();
}


}
