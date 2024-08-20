<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\packages;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackagesController extends Controller
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

        $lesson = packages::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $subject->id,
            'teacher_id' => $request->teacher_id,  // Add teacher_id
        ]);

        return response()->json(['message' => 'packages created successfully', 'packages' => $lesson], 201);
    }







    public function getpackagesByteacherId($teacherId, $educationalLevel)
    {

        $lessons = packages::where('teacher_id', $teacherId)
            ->where('educational_level_id', $educationalLevel) // Adjust according to your column name
            ->with(['educationalLevel']) // Eager load relationships
            ->get();

        if ($lessons->isEmpty()) {
            return response()->json(['message' => 'No packages found for this teacher at the specified educational level.'], 404);
        }

        return response()->json(['packages' => $lessons], 200);
    }


    public function destroy($id)
    {
        $Lesson = packages::find($id);

        if (!$Lesson) {
            return response()->json(['message' => 'packages not found.'], 404);
        }

        $Lesson->delete();

        return response()->json(['message' => 'packages deleted successfully'], 200);
    }


}
