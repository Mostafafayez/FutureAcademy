<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoProgressController extends Controller
{
    // ➕ Add / Update progress
    public function store(Request $request)
    {
        $request->validate([
            'video_id'   => 'required|exists:videos,id',
            'percentage' => 'required|integer|min:0|max:100',
        ]);

        $user = $request->user();

        $user->videos()->syncWithoutDetaching([
            $request->video_id => [
                'percentage' => $request->percentage
            ]
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Progress saved successfully'
        ], 200);
    }

    // 📥 Get progress for one video
  public function show($videoId)
{
    $video = auth()->user()
        ->videos()
        ->where('video_id', $videoId)
        ->first();

    return response()->json([
        'percentage' => $video?->pivot->percentage ?? 0,
        'status' => $video?->pivot->status ?? 'not_started',
    ]);
}

    // 🗑 Delete progress
    public function destroy($videoId)
    {
        $user = auth()->user();
        $user->videos()->detach($videoId);

        return response()->json([
            'status' => true,
            'message' => 'Progress deleted'
        ]);
    }
}
