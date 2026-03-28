<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    public $timestamps = false;

  protected $fillable = [
        'image_url',
        'imageable_id',
        'imageable_id '
    ];


    protected $appends=['FullSrc'];


    // public function lesson()
    // {
    //     return $this->hasMany(Lesson::class);
    // }


      public function imageable()
    {
        return $this->morphTo();
    }



  public function getFullSrcAttribute()
{
    return asset('storage/' . $this->image_url);
}
}
