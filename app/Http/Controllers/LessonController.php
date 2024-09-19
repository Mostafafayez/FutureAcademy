<?php
namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use App\Models\Lesson;
use Illuminate\Http\Request;


class lessonController extends Controller
{
    /**
     * Store a new lesson.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

            'teacher_id' => 'required|exists:teachers,id',
            'package_id' => 'required|exists:packages,id',
        ]);
        if ($request->teacher_id == 1) {
            $validated['image_id'] = 1;
        } elseif ($request->teacher_id == 2) {
            $validated['image_id'] = 6;
        }
        else  {
            $validated['image_id'] = 2;
        }
        $lesson = Lesson::create($validated);

        return response()->json($lesson, 200);
    }

    /**
     * Get lessons by package ID.
     */
    public function getByPackageId($packageId)
    {
        $lessons = Lesson::with('image')
        ->where('package_id', $packageId)->get();

        if ($lessons->isEmpty()) {
            return response()->json(['message' => 'No lessons found'], 404);
        }

        return response()->json($lessons);
    }

    /**
     * Delete a lesson by ID.
     */
    public function destroy($id)
    {
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->json(['message' => 'Lesson not found'], 404);
        }

        $lesson->delete();

        return response()->json(['message' => 'Lesson deleted'], 200);
    }





    public function getLessonsByTeacherIds($teacherId)
    {
        // Define the array of educational level IDs


        // Retrieve lessons for the specified teacher and educational level IDs
        $lessons = Lesson::take(9)->get();


        if ($lessons->isEmpty()) {
            return response()->json(['message' => 'No lessons found for this teacher at the specified educational levels.'], 404);
        }

        return response()->json(['lessons' => $lessons], 200);
    }



    public function getLessonsforassistant()
    {
        // Retrieve lessons for the specified teacher ID
        $lessons = Lesson::select('id', 'title', 'description', 'description_assistant','teacher_id') // Specify all columns except 'teacher_id'
        ->with('teacher:id,name')
        ->get();

        // Check if lessons were found
        if ($lessons->isEmpty()) {
            return response()->json(['message' => 'No lessons found for this teacher.'], 404);
        }

        // Return the lessons in the response
        return response()->json(['lessons' => $lessons], 200);
    }












    // public function getUUID(): JsonResponse
    // {
    //     try {
    //         $process = new Process(['wmic', 'csproduct', 'get', 'uuid']);
    //         $process->run();

    //         if (!$process->isSuccessful()) {
    //             throw new ProcessFailedException($process);
    //         }

    //         $output = $process->getOutput();
    //         $output = trim($output);

    //         // Extract UUID from Windows command output
    //         $lines = explode("\n", $output);
    //         $uuid = trim($lines[1] ?? '');

    //         // Clean up any unwanted characters
    //         $uuid = preg_replace('/[^a-fA-F0-9-]/', '', $uuid);

    //         return response()->json(['uuid' => $uuid]);
    //     } catch (\Exception $e) {
    //         Log::error('Error fetching UUID: ' . $e->getMessage());
    //         return response()->json(['error' => 'Failed to fetch UUID'], 500);
    //     }
    // }
}
