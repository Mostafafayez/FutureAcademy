<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DailyService;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function create(Request $request, DailyService $dailyService)
    {
        $room = $dailyService->createRoom([
            'name' => 'room-' . time(),

            'properties' => [
                'enable_chat' => true,
                'enable_screenshare' => true,
                'start_video_off' => false,

                // auto delete after use
                'exp' => now()->addHours(2)->timestamp,
            ]
        ]);

        return response()->json([
            'success' => true,
            'data' => $room,
        ], 200);
    }

    public function token(Request $request, DailyService $dailyService)
    {
        $token = $dailyService->createMeetingToken([
            'properties' => [
                'room_name' => $request->room_name,

                // user permissions
                'is_owner' => false,

                // expiration
                'exp' => now()->addHour()->timestamp,
            ]
        ]);

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }
}

