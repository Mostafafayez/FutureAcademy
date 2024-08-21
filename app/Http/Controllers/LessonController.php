<?php
namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use App\Models\Lesson;
use Illuminate\Http\Request;


class LessonController extends Controller
{
    /**
     * Store a new lesson.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'package_id' => 'required|exists:packages,id',
        ]);

        $lesson = Lesson::create($validated);

        return response()->json($lesson, 200);
    }

    /**
     * Get lessons by package ID.
     */
    public function getByPackageId($packageId)
    {
        $lessons = Lesson::where('package_id', $packageId)->get();

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
        $lessons = Lesson::take(6)->get();


        if ($lessons->isEmpty()) {
            return response()->json(['message' => 'No lessons found for this teacher at the specified educational levels.'], 404);
        }

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
