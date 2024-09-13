<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['image'];



    protected $appends=['FullSrc'];


    public function getFullSrcAttribute()  {
        return asset('storage/'.$this->image);

    }
}
