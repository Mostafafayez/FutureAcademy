<?php

namespace App\Http\Controllers;

use App\Models\TeacherVideo;
use Illuminate\Http\Request;

class TeacherVideoController extends Controller
{
    public function store(Request $request)
{
    $request->validate([

            'teacher_id'=>'required|exists:teachers,id',

            'title'=>'required|string|max:255',

            'description'=>'nullable|string',

            'video_url'=>'required|url',

            'thumbnail'=>'nullable|string',

            'status'=>'nullable|boolean',

    ]);

    return TeacherVideo::create($request->all());
}


public function index()
{
    return TeacherVideo::with('teacher')
        ->orderBy('sort_order')
        ->get();
}
public function getByTeacher($teacherId)
{
    return TeacherVideo::where('teacher_id',$teacherId)
        ->where('status',1)
        ->orderBy('sort_order')
        ->get();
}
public function getByEducationalLevel($levelId)
{
    return TeacherVideo::whereHas('teacher.educationalLevels', function ($q) use ($levelId) {

        $q->where('educational_levels.id',$levelId);

    })
    ->with('teacher')
    ->orderBy('sort_order')
    ->get();
}

}
