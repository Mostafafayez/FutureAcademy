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
               'phone' => 'required|numeric|digits_between:8,15|unique:users,phone',
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
    // Validate
    $request->validate([
        'phone' => 'required|numeric',
        'password' => 'required|string|min:6',
    ]);

    // Find teacher by phone
    $teacher = teacher::where('phone', $request->phone)->first();

    if (!$teacher || !Hash::check($request->password, $teacher->password)) {
        return response()->json([
            'status' => false,
            'message' => 'The provided credentials are incorrect.',
        ], 403);
    }

    // Load relations
    $teacher->load(['image', 'subject', 'educationalLevels']);

    // Create token
    $token = $teacher->createToken('personalAccessToken')->plainTextToken;

    return response()->json([
        'status' => true,
        'message' => 'Login successful',
        'teacher' => $teacher,
        'token' => $token,
    ], 200);
}


}
