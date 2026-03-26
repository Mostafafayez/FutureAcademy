<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class teacherStudentsProgress extends Controller
{
    public function teacherStudentsProgress(Request $request)
{


   $teacher = Auth::guard('teacher')->user();

    if (!$teacher) {
        return response()->json([
            'status' => false,
            'message' => 'sss   ',
            'teacher'  =>  $teacher,
        ], 401);
    }

    // 1️⃣ احصل على كل المحاضرات التي يدرسها هذا المدرس
    $lessons = Lesson::where('teacher_id', $teacher->id)->pluck('id')->toArray();

    // 2️⃣ احصل على كل الطلاب الذين شاهدوا أي محاضرة لهذا المدرس
    $students = User::whereHas('lessons', function($q) use ($lessons) {
        $q->whereIn('lessons.id', $lessons);
    })->with(['lessons' => function($q) use ($lessons) {
        $q->whereIn('lessons.id', $lessons)
          ->select('lessons.id', 'title')
          ->withPivot('percentage')
        //   ->withPivot('status');
    ;}])->get();

    // 3️⃣ إعادة البيانات
    $result = $students->map(function($student) {
        return [
            'id' => $student->id,
            'name' => $student->name,
            'phone' => $student->phone,
            'lessons' => $student->lessons->map(function($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'percentage' => $lesson->pivot->percentage,
                    'status' => $lesson->pivot->status,
                ];
            }),
        ];
    });

    return response()->json([
        'status' => true,
        'students' => $result
    ]);
}
}
