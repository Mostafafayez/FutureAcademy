<?php

namespace App\Http\Controllers;

use App\Models\EducationalLevel;
use App\Models\Subject;
use App\Models\teacher;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Validator;

class authteacher extends Controller
{ public function signUp(Request $request)
        {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255|unique:teachers',
                'description' =>'required|string|max:255',
                'password' => 'required|string|min:6',
                 'subject_id' => 'required|exists:subjects,id',
                 'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                   'educational_levels' => 'required|array',
                      'educational_levels.*' => 'exists:educational_levels,id'           ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }


            // Create the user
            $teacher = teacher::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'description' => $request->description,
                'password' => Hash::make($request->password),
                'subject_id' => $request->subject_id,

            ]);
                    $teacher->educationalLevels()->sync($request->educational_levels);

                      if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $path = $file->store('teachers', 'public');

                    $teacher->image()->create([
                        'image_url' => $path
                    ]);
                }


// ✅ load relations
$teacher->load(['subject', 'educationalLevels', 'image']);



                return response()->json([
                    'status' => true,
                    'message' => 'Teacher created successfully',
                    'data' => $teacher
                ], 200);
            }

public function login(Request $request)
{
    // 1️⃣ Validate the input
    $request->validate([
        'phone' => 'required|string|max:255',
        'password' => 'required|string|min:6',
    ]);

    // 2️⃣ Prepare credentials
    $credentials = $request->only('phone', 'password');

    // 3️⃣ Attempt authentication using 'teachers' guard
    if (!Auth::guard('teachers')->attempt($credentials)) {
        return response()->json([
            'status' => false,
            'message' => 'The provided credentials are incorrect.',
        ], 403);
    }

    // 4️⃣ Authenticated teacher
    $teacher = Auth::guard('teachers')->user();

    // 5️⃣ Load relations (image, subject, educational levels)
    $teacher->load(['image', 'subject', 'educationalLevels']);

    // 6️⃣ Generate personal access token
    $token = $teacher->createToken('personalAccessToken')->plainTextToken;

    // 7️⃣ Return clean response
    return response()->json([
        'status' => true,
        'message' => 'Login successful',
        'teacher' => $teacher,
        'token' => $token,
    ], 200);
}



}
