<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eductional_level_teacher extends Model
{
    use HasFactory;

public $timestamps=false;


protected $fillable = [
    'id',
    'eductional_level_id',
    'teacher_id'
];

  

}
