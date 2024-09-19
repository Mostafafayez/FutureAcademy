<?php
namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
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

        // Modify the URL by replacing 'view' with 'preview'
        $url = str_replace('/view', '/preview', $request->url);

        $video = Video::create([
            'title' => $request->title,
            'description' => $request->description,
            'url' => $url,
            'lesson_id' => $request->lesson_id,
        ]);

        return response()->json(['message' => 'Video created successfully', 'video' => $video], 201);
    }

    // public function getByLessonId($lessonId)
    // {
    //     $videos = Video::where('lesson_id', $lessonId)->get();

    //     if ($videos->isEmpty()) {
    //         return response()->json(['message' => 'No videos found for this lesson.'], 404);
    //     }

    //     return response()->json(['videos' => $videos], 200);
    // }



    public function getByLessonsId($lessonId)
    {
        $videos = Video::where('lesson_id', $lessonId)->get();

        if ($videos->isEmpty()) {
            return response()->json(['message' => 'No videos found for this lesson.'], 404)
                ->header('Access-Control-Allow-Origin', 'http://localhost:3000')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        }

        // Add the video_id to each video in the response
        $videosWithIds = $videos->map(function ($video) {
            $videoUrl = $video->url;
            // Use regex to extract video ID from the YouTube URL
            preg_match('/(?:youtube\.com\/(?:shorts\/|watch\?v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches);
            $videoId = $matches[1] ?? null;

            return [
                'id' => $video->id,
                'lesson_id' => $video->lesson_id,
                'url' => $video->url,
                'title' => $video->title,
                'description' => $video->description,
                'video_id' => $videoId,
            ];
        });

        return response()->json(['videos' => $videosWithIds], 200)
            ->header('Access-Control-Allow-Origin', 'http://localhost:3000')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
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



    public function getByLessonId($lessonId)
    {

        $videos = Video::where('lesson_id', $lessonId)
                       ->select('id','title', 'description', 'url')
                       ->get();


        if ($videos->isEmpty()) {
            return response()->json(['message' => 'No videos found for this lesson.'], 404);
        }

        return response()->json($videos);
    }






public function getEncryptionByLessonId($lessonId)
{
    $videos = Video::where('lesson_id', $lessonId)
                   ->select('title', 'description', 'url')
                   ->get();

    if ($videos->isEmpty()) {
        return response()->json(['message' => 'No videos found for this lesson.'], 404);
    }

    $videos->transform(function ($video) {
        $video->url = Crypt::encryptString($video->url);
        return $video;
    });

    return response()->json($videos);
}


}
