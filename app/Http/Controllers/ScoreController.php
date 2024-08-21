<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    // Store a new score
    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'score' => 'required|integer',
            'user_id' => 'required|exists:users,id',
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        // Check if the score already exists for the given user and lesson
        $existingScore = Score::where('user_id', $validatedData['user_id'])
                              ->where('lesson_id', $validatedData['lesson_id'])
                              ->first();

        if ($existingScore) {
            // If the score already exists, return a message
            return response()->json(['message' => 'تم حفظ الدرجه الاولي  .', 'score' => $existingScore], 200);
        }

        // Create the score if it doesn't exist
        $score = Score::create($validatedData);

        return response()->json(['message' => 'Score created successfully.', 'score' => $score], 201);
    }

    // Get scores by user_id
    public function getByUserId($user_id)
    {
        $scores = Score::where('user_id', $user_id)->get();

        if ($scores->isEmpty()) {
            return response()->json(['message' => 'No scores found for this user.'], 404);
        }

        return response()->json(['scores' => $scores], 200);
    }

    // Get all scores with user and lesson details
    public function getAllScores()
    {
        $scores = Score::with(['user:id,name,phone', 'lesson:id,title,description_assistant'])->get();

        if ($scores->isEmpty()) {
            return response()->json(['message' => 'No scores found.'], 404);
        }

        return response()->json(['scores' => $scores], 200);
    }

}
