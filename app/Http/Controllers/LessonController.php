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
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $lesson = Lesson::create([
        'title' => $validated['title'],
        'description' => $validated['description'] ?? null,
        'teacher_id' => $validated['teacher_id'],
        'package_id' => $validated['package_id'],
    ]);


    if ($request->hasFile('image')) {
        $filePath = $request->file('image')->store('images/lessons', 'public');

        $lesson->image()->create([
            'url' => $filePath
        ]);
    }

    return response()->json([
        'message' => 'Lesson created successfully',
        'lesson' => $lesson->load('image')
    ], 201);
}



public function update(Request $request, $id)
{
    $lesson = Lesson::findOrFail($id);

    $validated = $request->validate([
        'title' => 'sometimes|string|max:255',
        'description' => 'nullable|string',
        'teacher_id' => 'sometimes|exists:teachers,id',
        'package_id' => 'sometimes|exists:packages,id',
        'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // update only sent fields
    $lesson->update($validated);

    // handle image update
    if ($request->hasFile('image')) {

        // delete old image (optional but recommended)
        if ($lesson->image) {
            \Storage::disk('public')->delete($lesson->image->url);
            $lesson->image()->delete();
        }

        $filePath = $request->file('image')->store('images/lessons', 'public');

        $lesson->image()->create([
            'url' => $filePath
        ]);
    }

    return response()->json([
        'message' => 'Lesson updated successfully',
        'lesson' => $lesson->load('image')
    ], 200);
}

    /**
     * Get lessons by package ID.
     */
public function getByPackageId($packageId)
{
    $user = auth()->user();

    $lessons = Lesson::with(['image', 'users'])
        ->where('package_id', $packageId)
        ->get();

    if ($lessons->isEmpty()) {
        return response()->json(['message' => 'No lessons found'], 404);
    }

    $lessonsWithProgress = $lessons->map(function ($lesson) use ($user) {

        $userLesson = $lesson->users()
            ->where('user_id', $user->id)
            ->first();

        return [
            'id'         => $lesson->id,
            'title'      => $lesson->title,

            'image'      => $lesson->image?->image_url,

            'percentage' => $userLesson?->pivot?->percentage ?? 0,
            'status'     => $userLesson?->pivot?->status ?? 'not_started',
        ];
    });

    return response()->json($lessonsWithProgress);
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
        $lessons = Lesson::take(9)->image()->get();


        if ($lessons->isEmpty()) {
            return response()->json(['message' => 'No lessons found for this teacher at the specified educational levels.'], 404);
        }

        return response()->json(['lessons' => $lessons], 200);
    }



    public function getLessonsforassistant()
    {
        // Retrieve lessons for the specified teacher ID
        $lessons = Lesson::select('id', 'title', 'description', 'description_assistant','teacher_id') // Specify all columns except 'teacher_id'
        ->with('teacher:id,name','image')
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
