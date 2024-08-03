<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'subject' => 'required|string|exists:subjects,name',
            'teacher_id' => 'required|exists:teachers,id',  // Validate teacher_id
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Retrieve the subject ID based on the name
        $subject = Subject::where('name', $request->subject)->first();

        if (!$subject) {
            return response()->json(['message' => 'Subject not found.'], 404);
        }

        $lesson = Lesson::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $subject->id,
            'teacher_id' => $request->teacher_id,  // Add teacher_id
        ]);

        return response()->json(['message' => 'Lesson created successfully', 'lesson' => $lesson], 201);
    }







    public function getLessonsByteacherId($teacherId)
    {
        $lessons = Lesson::where('teacher_id', $teacherId)->with('teacher')->get();

        if ($lessons->isEmpty()) {
            return response()->json(['message' => 'No lessons found for this teacher.'], 404);
        }

        return response()->json(['lessons' => $lessons], 200);
    }





    public function destroy($id)
    {
        $Lesson = Lesson::find($id);

        if (!$Lesson) {
            return response()->json(['message' => 'Lesson not found.'], 404);
        }

        $Lesson->delete();

        return response()->json(['message' => 'Lesson deleted successfully'], 200);
    }


}
