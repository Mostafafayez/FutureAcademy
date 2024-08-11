<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lesson_id' => 'required|exists:lessons,id',
            'question_text' => 'required|string',
            'answer_type' => 'required|in:multiple_choice,open_ended',
            'options' => 'nullable|json',
            'correct_answer' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $question = Question::create($request->all());

        return response()->json($question, 201);
    }



    public function getByLessonId($lessonId)
    {
        // Validate that the lessonId is a valid number
        if (!is_numeric($lessonId)) {
            return response()->json(['message' => 'Invalid lesson ID'], 400);
        }

        // Retrieve questions for the given lesson ID
        $questions = Question::where('lesson_id', $lessonId)->get();

        if ($questions->isEmpty()) {
            return response()->json(['message' => 'No questions found for this lesson.'], 404);
        }

        return response()->json($questions, 200);
    }
}
