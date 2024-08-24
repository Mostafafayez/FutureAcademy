<?php

namespace App\Http\Controllers;

use App\Models\MCQ;
use App\Models\MCQS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MCQController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'string|max:1000',
            'url' => 'required|url',
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $mcq = MCQS::create([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url,
            'lesson_id' => $request->lesson_id,
        ]);

        return response()->json(['message' => 'MCQ created successfully', 'mcq' => $mcq], 201);
    }

    public function getByLessonId($lessonId)
    {
        $mcqs = MCQS::where('lesson_id', $lessonId)->get();

        if ($mcqs->isEmpty()) {
            return response()->json(['message' => 'No MCQs found for this lesson.'], 404);
        }

        return response()->json(['mcqs' => $mcqs], 200);
    }


    public function getByPackageId($id)
    {
        // Retrieve the MCQs associated with the package ID
        $mcqs = MCQS::with('packages')
                    ->where('packages_id', $id)
                    ->get();

        // Check if any MCQs are found
        if ($mcqs->isEmpty()) {
            return response()->json(['message' => 'No MCQs found for this package'], 404);
        }

        // Return the MCQs along with the package information
        return response()->json(['package_name' => $mcqs->first()->packages->name, 'mcqs' => $mcqs], 200);
    }

    public function destroy($id)
    {
        $mcq = MCQs::find($id);

        if (!$mcq) {
            return response()->json(['message' => 'MCQ not found.'], 404);
        }

        $mcq->delete();

        return response()->json(['message' => 'MCQ deleted successfully.'], 200);
    }
}
