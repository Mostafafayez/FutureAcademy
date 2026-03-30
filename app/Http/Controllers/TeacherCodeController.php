<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Models\Code;

class TeacherCodeController extends Controller
{


public function store(Request $request)
{
    $request->validate([
        'expires_at' => 'required|date',
    ]);

    $teacher = auth('teacher')->user();

    if (!$teacher) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }

    $code = Code::create([
        'expires_at' => $request->expires_at,
        'teacher_id' => $teacher->id, // 👈 أهم سطر
    ]);

    return response()->json([
        'status' => true,
        'code' => $code
    ], 201);
}




public function myCodes(Request $request)
{
    $teacher = auth('teacher')->user();

    if (!$teacher) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthenticated'
        ], 401);
    }

    $codes = Code::where('teacher_id', $teacher->id)->get();
     $codes->load('user');
    return response()->json([
        'status' => true,
        'codes' => $codes
    ]);
}
}
