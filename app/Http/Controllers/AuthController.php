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
    // Validate
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|numeric|digits_between:8,15|unique:users,phone',
        'password' => 'required|string|min:6',
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'educational_level_id' => 'required|exists:educational_levels,id',
    ]);

    // Create user
    $user = User::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'educational_level_id' => $request->educational_level_id,
    ]);

    // Handle image
    if ($request->hasFile('image')) {
        $file = $request->file('image');

        $path = $file->store('students', 'public');

        $user->image()->create([
            'image_url' => $path
        ]);
    }


    $user->load('image');

    return response()->json([
        'status' => true,
        'user' => $user
    ], 201);
}
public function login(Request $request)
{
    // 1️⃣ Validate
    $validator = Validator::make($request->all(), [
        'phone' => 'required|numeric',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    // 2️⃣ Attempt login
    $credentials = $request->only('phone', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json([
            'status' => false,
            'message' => 'The phone or password is incorrect.'
        ], 403);
    }

    // 3️⃣ Get authenticated user
    $user = Auth::user();



    // 4️⃣ Delete old tokens
    $user->tokens()->delete();

    // 5️⃣ Create new token
    $token = $user->createToken('personalAccessToken')->plainTextToken;

    // 6️⃣ Load relations
    $user->load(['educationalLevel', 'image']);

    // 7️⃣ Return response بشكل نظيف
    return response()->json([
        'status' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone,
            'educational_level' => $user->educationalLevel->name ?? null,
            'image' => $user->image ? asset('storage/' . $user->image->image_url) : null,
        ],
        'token' => $token,
    ], 200);
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
        if ( $request->user()->role === 'admin') {
            return response()->json( 'iam admin');
        }

    }





public function mySubscriptions(Request $request)
{
    $user = Auth::user(); // جلب المستخدم الحالي من token

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not authenticated'
        ], 401);
    }

    // 1️⃣ الدروس المشترك فيها
    $packages = $user->subscribedLessons()->get();

    // 2️⃣ المجموعات (Packages) مباشرة
    // $packages = $lessons->pluck('package')->unique('id')->values();

    return response()->json([
        'status' => true,
        // 'lessons' => $lessons,
        'packages' => $packages,
    ]);
}




public function resetPassword(Request $request)
{
    // 1️⃣ Validate
    $request->validate([
        'phone' => 'required|numeric|exists:users,phone',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // 2️⃣ Get user
    $user = User::where('phone', $request->phone)->first();

    // 3️⃣ Update password
    $user->update([
        'password' => Hash::make($request->password)
    ]);

    // 4️⃣ حذف التوكنز القديمة (مهم 🔥)
    // $user->tokens()->delete();

    return response()->json([
        'status' => true,
        'message' => 'Password updated successfully'
    ], 200);
}
    }
