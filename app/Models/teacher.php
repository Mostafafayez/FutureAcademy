<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
    class teacher extends Authenticatable
    {
        protected $table = 'teachers';
        use HasApiTokens, HasFactory, Notifiable,SoftDeletes;
        protected $fillable = [
            'name', 'phone','description',  'subject_id','password'
        ];
        public $timestamps = true;
        protected $hidden = [
            'password',
            'remember_token',
        ];

        public function code()
{
    return $this->hasMany(Code::class);
}
        public function image()
            {
                return $this->morphOne(Image::class, 'imageable');
            }

                            protected $appends = ['image_url'];

                    public function getImageUrlAttribute()
                    {
                        return $this->image?->FullSrc;
                    }
        // Define relationships if applicable
        public function educationalLevels()
    {
        return $this->belongsToMany(EducationalLevel::class, 'eductional_level_teacher', 'teacher_id', 'educational_level_id');
    }


    public function messages()
{
    return $this->hasMany(Message::class);
}

        public function subject() {
            return $this->belongsTo(Subject::class);

        }



        public function lessons()
        {
            return $this->hasMany(Lesson::class, 'teacher_id');
        }

        public function getFullSrcAttribute()  {
            return asset('storage/'.$this->image);

        }
public function offers()
{
    return $this->hasMany(Offer::class);
}

            public function codes()
            {
                return $this->hasManyThrough(
                    Code::class,     // Final model
                    packages::class,   // Intermediate model
                    'teacher_id',    // FK in lessons table
                    'lesson_id',     // FK in codes table
                    'id',            // PK in teachers table
                    'id'             // PK in lessons table
                );
            }

    }
