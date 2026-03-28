<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['title', 'description', 'package_id','description_assistant','teacher_id','order'];

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
            return $this->morphOne(Image::class, 'imageable');
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


  /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    // التحقق من أن المستخدم مؤهل لدخول الدرس public function canAccess(User $user, $minPassScore = 50)
  public function canAccess(User $user, $minPassScore = 50)
    {
            dump('Current : ' . $this);

        // إذا الدرس الأول، السماح مباشرة
        if ($this->order == 1) {
            return true;
        }

        // احصل على الدرس السابق في نفس الباكدج
        $previousLesson = self::where('package_id', $this->package_id)
            ->where('order', $this->order - 1)
            ->first();

        if (!$previousLesson) {
            return false; // لا يوجد درس سابق
        }

        // تحقق من نتيجة الطالب للدرس السابق
        $score = $previousLesson->score()->where('user_id', $user->id)->first();

        if (!$score || $score->score < $minPassScore) {
            return false;
        }

        return true;
    }
}


