<?php
namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'url' => 'required|url',
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url,
            'lesson_id' => $request->lesson_id,
        ]);

        return response()->json(['message' => 'Video created successfully', 'video' => $video], 201);
    }

    public function getByLessonId($lessonId)
    {
        $videos = Video::where('lesson_id', $lessonId)->get();

        if ($videos->isEmpty()) {
            return response()->json(['message' => 'No videos found for this lesson.'], 404);
        }

        return response()->json(['videos' => $videos], 200);
    }

    public function destroy($id)
    {
        $video = Video::find($id);

        if (!$video) {
            return response()->json(['message' => 'Video not found.'], 404);
        }

        $video->delete();

        return response()->json(['message' => 'Video deleted successfully.'], 200);
    }
}
