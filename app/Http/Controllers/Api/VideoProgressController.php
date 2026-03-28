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
        'lesson_id'   => 'required|exists:lessons,id',
        'percentage' => 'required|integer|min:0|max:100',
    ]);

    $user = $request->user();

    // هات النسبة القديمة لو موجودة
    $existing = $user->lessons()
        ->where('lesson_id', $request->lesson_id)
        ->first();

    if ($existing) {
        $oldPercentage = $existing->pivot->percentage;

        // ❗ امنع تقليل النسبة
        if ($request->percentage > $oldPercentage) {
            $user->lessons()->updateExistingPivot(
                $request->lesson_id,
                ['percentage' => $request->percentage]
            );
        }
    } else {
        // لو اول مرة
        $user->lessons()->attach($request->lesson_id, [
            'percentage' => $request->percentage
        ]);
    }

    return response()->json([
        'status' => true,
        'message' => 'Progress saved successfully'
    ], 200);
}
    // 📥 Get progress for one video
public function show($lessonid)
{
    $user = auth()->user();

    $video = $user->lessons()
        ->where('lesson_id', $lessonid)
        ->first();

    return response()->json([
        // 'video_id'   => (int) $lessonid,
        'percentage' => $video?->pivot->percentage ?? 0,
        'status'     => $video?->pivot->status ?? 'not_started',
    ]);
}


    // 🗑 Delete progress
    public function destroy($lessonid)
    {
        $user = auth()->user();
        $user->lessons()->detach($lessonid);

        return response()->json([
            'status' => true,
            'message' => 'Progress deleted'
        ]);
    }
}
