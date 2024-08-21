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
            $credentials = ['phone' => $request->phone, 'password' => $request->password];
            if (Auth::attempt($credentials)) {
                $user = Auth::user(); // Retrieve the authenticated user

                // Check the status of the user
                if ($user->status === 'pending') {
                    // Return a message indicating the account is still pending
                    return response()->json([
                        'message' => 'Your account is still pending approval. Please wait for the approval process to complete.'
                    ], 403);
                } elseif ($user->status === 'approval') {
                    // Generate a token and log in the user
                    $token = $user->createToken('personalAccessToken')->plainTextToken;

                    return response()->json([
                        'user' => $user,
                        'token' => $token,
                        // 'educational_level' => $user->educationalLevel->name ?? null
                    ], 200);
                } else {
                    // Handle any other status if necessary
                    return response()->json([
                        'message' => 'Your account status does not allow login at this time.'
                    ], 403);
                }
            } else {
                // Authentication failed
                throw ValidationException::withMessages([
                    'phone' => ['The provided credentials are incorrect.'],
                ]);
            }
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

    }
