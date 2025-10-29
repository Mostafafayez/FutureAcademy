<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\EducationalLevel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
// use Laravel\Sanctum\HasApiTokens;



    class AuthController extends Controller
    {
        /**
         * Handle user registration.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\JsonResponse
         */
        public function signUp(Request $request)
        {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:6',
                'educational_level' => 'required|string|exists:educational_levels,name',
            ], [
                'educational_level.exists' => 'The selected educational level is invalid.',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Find the educational level by name
            $educationalLevel = EducationalLevel::where('name', $request->educational_level)->first();

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'educational_level_id' => $educationalLevel->id,

            ]);

            // Return the created user
            return response()->json(['user' => $user], 201);
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

    // Check if the user exists and the password matches
    $credentials = $request->only('phone', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user(); // Retrieve the authenticated user

//         if (substr($user->phone, -1) === '$') {
//             return response()->json([
//                 'message' => ' "تم تعطيل الاكونت . برجاء التواصل مع مشرفين المدرس الخاص بك"
// ',
//             ], 403);
//         }
        // Generate the token for the user
        $token = $user->createToken('personalAccessToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            // 'educational_level' => $user->educationalLevel->name ?? null // Uncomment if educational level is needed
        ], 200);
    } else {
        // Authentication failed
        return response()->json([
            'message' => 'The Email or password is incorrect.',
        ], 403);
    }
}







        public function isApproved($id)
        {
            // Find the user by ID
            $user = User::find($id);

            // Check if the user exists
            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            // Set the user's status to 'approval'
            $user->status = 'approval';

            // Save the changes to the database
            $user->save();

            // Return a success response
            return response()->json(['message' => 'User approved successfully.'], 200);
        }

        public function updatePassword(Request $request, $user_id)
        {
            // Find the user by their ID
            $user = User::find($user_id);

            // Check if the user exists
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Validate the request
            $validated = $request->validate([
                'new_password' => 'required|string|min:8',
            ]);

            // Update the user's password
            $user->update([
                'password' => Hash::make($validated['new_password']),
            ]);

            return response()->json(['message' => 'Password updated successfully'], 200);
        }






public function logout(Request $request)
{
    // Ensure the user is authenticated
    if (!Auth::check()) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Get the authenticated user
    $user = Auth::user();

    // Revoke all tokens for the user
    $user->tokens()->delete();

    return response()->json(['message' => 'Logged out successfully'], 200);
}




public function userinfo() {
    $users = User::with('educationalLevel:name,id')->get(['name', 'phone', 'educational_level_id']);

    $usersData = $users->map(function ($user) {
        return [
            'name' => $user->name,
            'phone' => $user->phone,
            'educational_level' => $user->educationalLevel->name ?? 'N/A',
        ];
    });

    return response()->json($usersData);
}




   public function handle(Request $request)
    {
        if ($request->user() && $request->user()->role === 'admin') {
            return 'amin';
        }

    }
    }
