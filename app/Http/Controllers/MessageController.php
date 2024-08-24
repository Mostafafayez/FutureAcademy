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




    public function getUserMessages()
    {
        // Retrieve all users with their messages and educational level
        $users = User::with(['messages', 'educationalLevel'])->get();

        // Prepare the response data
        $data = $users->map(function ($user) {
            return [
                'user_name' => $user->name,
                'user_phone' => $user->phone,
                'educational_level' => $user->educationalLevel->name ?? 'N/A',
                'messages' => $user->messages->pluck('message'), // Retrieve only the message content
            ];
        });

        // Return the data as a JSON response
        return response()->json($data);
    }
}
