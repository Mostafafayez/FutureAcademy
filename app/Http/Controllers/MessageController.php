<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

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
}
