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
{

    public function signUp(Request $request)
{
    // Validate the request including the image upload
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'phone' => 'required|string|max:255',
        'password' => 'required|string|min:6',
        'subject' => 'required|string|exists:subjects,name',
        'educational_level' => 'required|string|exists:educational_levels,name',
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validate the image file
    ], [
        'educational_level.exists' => 'The selected educational level is invalid.',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Store the image in the 'images' folder under the 'public' disk
    $imagePath = $request->file('image')->store('images', 'public');

    // Find the educational level by name
    $educationalLevel = EducationalLevel::where('name', $request->educational_level)->first();
    $subject = Subject::where('name', $request->subject)->first();

    // Create the teacher
    $teacher = Teacher::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'image' => $imagePath, // Save the image path in the database
        'password' => Hash::make($request->password),
        'subject_id' => $subject->id,
        'educational_level_id' => $educationalLevel->id,
        'description' => $request->description,
    ]);

    // Return the created teacher
    return response()->json(['user' => $teacher], 201);
}


    public function login(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the teacher exists and the password matches
        $credentials = $request->only('phone', 'password');

        // Use the 'teachers' guard for authentication
        if (Auth::guard('teachers')->attempt($credentials)) {
            $teacher = Auth::guard('teachers')->user(); // Retrieve the authenticated teacher

            // Generate the token for the teacher
            $token = $teacher->createToken('personalAccessToken')->plainTextToken;

            return response()->json([
                'teacher' => $teacher,
                'token' => $token,
                // 'educational_level' => $teacher->educationalLevel->name ?? null // Uncomment if educational level is needed
            ], 200);
        } else {
            // Authentication failed
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 403);
        }
    }




}
