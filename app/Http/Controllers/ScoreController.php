<?php

namespace App\Http\Controllers;

use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    // Store a new score
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'score' => 'required|integer',
            'user_id' => 'required|exists:users,id',
            'lesson_id' => 'required|exists:lessons,id',
        ]);

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
        $scores = Score::with(['user:name,educational_level', 'lesson:title,description'])->get();

        if ($scores->isEmpty()) {
            return response()->json(['message' => 'No scores found.'], 404);
        }

        return response()->json(['scores' => $scores], 200);
    }
}
