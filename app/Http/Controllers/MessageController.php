<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\User;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'message' => $validatedData['message'],
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Message added successfully!', 'data' => $message], 201);
    }





    public function getUserMessages($id)
    {
        // Retrieve the user with their messages and educational level
        $user = User::with(['messages', 'educationalLevel'])->find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Prepare the response data
        $data = [
            'name' => $user->name,
            'phone' => $user->phone,
            'educational_level' => $user->educationalLevel->name ?? 'N/A', // Retrieve the educational level's name
            'messages' => $user->messages->pluck('message'), // Retrieve only the message content
        ];

        // Return the data as a JSON response
        return response()->json($data);
    }
}
