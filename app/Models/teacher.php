<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
    class teacher extends Authenticatable
    {
        protected $table = 'teachers';
        use HasApiTokens, HasFactory, Notifiable;
        protected $fillable = [
            'name', 'phone','image','description', 'educational_level_id', 'subject_id','password'
        ];
        public $timestamps = false;
        protected $hidden = [
            'password',
            'remember_token',
        ];

        protected $appends=['FullSrc'];
        // Define relationships if applicable
        public function educationalLevels()
    {
        return $this->belongsToMany(EducationalLevel::class, 'eductional_level_teacher', 'teacher_id', 'educational_level_id');
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
    }
