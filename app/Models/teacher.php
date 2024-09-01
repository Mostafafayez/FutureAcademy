<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
    class teascher extends Authenticatable
    {
        protected $table = 'teachers';
        use HasFactory ;
        protected $fillable = [
            'name', 'image', 'educational_level_id', 'subject_id'
        ];
        public $timestamps = false;


        protected $appends=['FullSrc'];
        // Define relationships if applicable
        public function educationalLevel()
        {
            return $this->belongsTo(EducationalLevel::class, 'educational_level_id');
        }

        public function subject()
        {
            return $this->belongsTo(Subject::class, 'subject_id');
        }


        public function lessons()
        {
            return $this->hasMany(Lesson::class, 'teacher_id');
        }

        public function getFullSrcAttribute()  {
            return asset('storage/'.$this->image);

        }
    }
