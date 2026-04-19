<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required|string',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $message = Message::create([
            'message' => $validatedData['message'],
            'user_id' => auth()->id(),
            'teacher_id' =>$validatedData['teacher_id'],
        ]);

        return response()->json(['message' => 'Message added successfully!', 'data' => $message], 201);
    }



    public function getMessagesByTeacher()
{
       $teacher = Auth::guard('teacher')->user();


         if (!$teacher) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }




    $messages = Message::where('teacher_id',$teacher->id )
       
        ->get();

    return response()->json([
        'message' => 'Messages retrieved successfully',
        'data' => $messages
    ], 200);
}



public function getUserMessages()
{
    $user = Auth::user();

    $user->load(['messages.teacher', 'educationalLevel']);

    $data = [
        'user_name' => $user->name,
        'user_phone' => $user->phone,
        'educational_level' => $user->educationalLevel->name ?? 'N/A',
        'messages' => $user->messages->map(function ($message) {
            return [
                'message' => $message->message,
                'teacher_name' => $message->teacher?->name ?? 'Unknown',
            ];
        }),
    ];

    return response()->json($data);
}


    public function getAllMessages()
    {
        // Retrieve all messages with related user and educational level
        $messages = Message::with(['user.educationalLevel'])->get();

        // Prepare the response data
        $data = $messages->map(function ($message) {
            return [
                'message' => $message->message,
                'user_name' => $message->user->name,
                'user_phone' => $message->user->phone,
                'educational_level' => $message->user->educationalLevel->name ?? 'N/A',
            ];
        });

        // Return the data as a JSON response
        return response()->json($data);
    }




}
