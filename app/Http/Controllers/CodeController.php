<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Artisan;
use App\Models\Code;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class CodeController extends Controller
{
    public function store(Request $request)
    {
        // Custom validation messages
        $messages = [
            'expires_at.required' => 'The expiration date field is required.',
            'expires_at.date' => 'The expiration date must be a valid date format. Example format: YYYY-MM-DD.',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), [
            'expires_at' => 'required|date',
        ], $messages);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create the new code
        $code = Code::create([
            'mac_address' => '', // Initialize with an empty string
            'expires_at' => $request->expires_at,
        ]);

        return response()->json(['message' => 'Code created successfully', 'code' => $code], 201);
    }


    public function storefixed()
    {
        // Create the new code with a fixed 'expires_at' date of 2026-09-07
        $code = Code::create([
            'mac_address' => '', // Initialize with an empty string
            'mac_address2' => '',
            'expires_at' => '2026-10-07', // Set the fixed expiration date
        ]);

        return response()->json(['message' => 'Code created successfully', 'code' => $code], 201);
    }
    public function mohamed_math()
    {
        // Create the new code with a fixed 'expires_at' date of 2026-09-07
        $code = Code::create([
            'mac_address' => '', // Initialize with an empty string
            'mac_address2' => 'null',
            'expires_at' => '2027-10-07', // Set the fixed expiration date
        ]);

        return response()->json(['message' => 'Code created successfully', 'code' => $code], 201);
    }


    public function get_mohamed_math()
    {
        $code = code :: where('expires_at' , '2027-07-07 ')
        ->get();
        // Create the new code with a fixed 'expires_at' date of 2026-09-07


        return response()->json(['message' => "Codes for Mr. Mohamed's Students", 'code' => $code], 201);
    }



    public function get_ashraf_codes()
    {
        $code = code :: where('expires_at' , '2026-09-07 ')
        ->get();
        // Create the new code with a fixed 'expires_at' date of 2026-09-07

        $count = $code->count();

        return response()->json(['message' => "Codes for Mr. Mohamed's Students",  'code_count' => $count, 'code' => $code], 201);
    }



    public function validateCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'mac_address' => 'string|max:255',
            'user_id' => 'required|exists:users,id',
            'lesson_id' => 'required|exists:packages,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $code = Code::where('code', $request->code)->first();

        if (!$code) {
            return response()->json(['message' => 'Invalid code.'], 404);
        }

        if (Carbon::now()->greaterThan($code->expires_at)) {
            return response()->json(['message' => 'Code has expired.'], 410);
        }

        if ($code->type === 'used') {
            return response()->json(['message' => 'Code is already used.'], 400);
        }

        // Check if the user is already subscribed to the lesson
        $existingSubscription = Code::where('user_id', $request->user_id)
                                    ->where('lesson_id', $request->lesson_id)
                                    ->where('type', 'used')
                                    ->first();

        if ($existingSubscription) {
            return response()->json(['message' => 'you already has a code for this package.'], 400);
        }

        // If the code type is 'notused', update the fields and set type to 'used'
        if ($code->type === 'notused') {
            $code->user_id = $request->user_id;
            $code->lesson_id = $request->lesson_id;
            $code->mac_address = $request->mac_address;

            $code->type = 'used';
            $code->save();

            return response()->json(['message' => 'Code validated and updated successfully.'], 200);
        }

        return response()->json(['message' => 'Unknown error occurred.'], 500);
    }
    // public function validateCodeForMacAddress2(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'code' => 'required|string',
    //         'mac_address2' => 'string|max:255',
    //         'user_id' => 'required|exists:users,id',
    //         'lesson_id' => 'required|exists:packages,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $code = Code::where('code', $request->code)->first();

    //     if (!$code) {
    //         return response()->json(['message' => 'Invalid code.'], 404);
    //     }

    //     if (Carbon::now()->greaterThan($code->expires_at)) {
    //         return response()->json(['message' => 'Code has expired.'], 410);
    //     }

    //     if ($code->type2 === 'used') {
    //         return response()->json(['message' => 'Code is already used .'], 400);
    //     }

    //     // Check if the user is already subscribed to the lesson using type2
    //     $existingSubscription = Code::where('user_id', $request->user_id)
    //                                 ->where('lesson_id', $request->lesson_id)
    //                                 ->where('type2', 'used')
    //                                 ->first();

    //     if ($existingSubscription) {
    //         return response()->json(['message' => 'You already have a code for this package .'], 400);
    //     }

    //     // If the code type2 is 'notused', update the fields and set type2 to 'used'
    //     if ($code->type2 === 'notused') {
    //         $code->user_id = $request->user_id;
    //         $code->lesson_id = $request->lesson_id;
    //         $code->mac_address2 = $request->mac_address2;

    //         $code->type2 = 'used';
    //         $code->save();

    //         return response()->json(['message' => 'Code validated and updated successfully .'], 200);
    //     }

    //     return response()->json(['message' => 'Unknown error occurred.'], 500);
    // }



    public function validateCodeForMacAddress2(Request $request)
{
    $validator = Validator::make($request->all(), [
        'code' => 'required|string',
        'mac_address2' => 'required|string|max:255',
        'user_id' => 'required|exists:users,id',
        'lesson_id' => 'required|exists:packages,id',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $code = Code::where('code', $request->code)->first();

    if (!$code) {
        return response()->json(['message' => 'Invalid code.'], 404);
    }

    if (Carbon::now()->greaterThan($code->expires_at)) {
        return response()->json(['message' => 'Code has expired.'], 410);
    }

    if ($code->type2 === 'used') {
        return response()->json(['message' => 'Code is already used.'], 400);
    }

    // Check if the user is already subscribed to the lesson using type2
    $existingSubscription = Code::where('user_id', $request->user_id)
                                ->where('lesson_id', $request->lesson_id)
                                ->where('type2', 'used')
                                ->first();

    if ($existingSubscription) {
        return response()->json(['message' => 'You already have a code for this package.'], 400);
    }

    // Ensure that mac_address2 is null before proceeding, and if not, validate
    if ($code->type2 === 'notused') {
        if ($code->mac_address2 !== null) {
            // Ensure the user_id and lesson_id in the DB match the request, and mac_address2 is null
            if ($code->user_id !== $request->user_id || $code->lesson_id !== $request->lesson_id) {
                return response()->json(['message' => 'Code is already used on another user.'], 400);
            }

            if ($code->mac_address2 !== null) {
                return response()->json(['message' => 'Code already used on a second device.'], 400);
            }
        }

        // If all checks pass, update mac_address2 and set type2 to 'used'
        $code->user_id = $request->user_id;
        $code->lesson_id = $request->lesson_id;
        $code->mac_address2 = $request->mac_address2;
        $code->type2 = 'used';
        $code->save();

        return response()->json(['message' => 'Code validated and updated successfully.'], 200);
    }

    return response()->json(['message' => 'Unknown error occurred.'], 500);
}


    public function getValidLessonsWithDetailsByUserId($userId)
    {
        // Retrieve all codes for the given user ID where the code is not expired
        $validCodes = Code::with('package') // Eager load the related package
            ->where('user_id', $userId)
            ->where('expires_at', '>', Carbon::now()) // Check if the expiration date is in the future
            ->get();

        // If no valid codes are found, return a message
        if ($validCodes->isEmpty()) {
            return response()->json(['message' => 'No valid lessons found for this user.'], 404);
        }

        // Extract the packages (lessons) from the valid codes
        $validPackages = $validCodes->map(function ($code) {
            return $code->package; // Get the related package (lesson)
        })->filter(); // Remove any null values just in case

        return response()->json(['valid_packages' => $validPackages->values()], 200);
    }




    public function getAllCodesWithUsers()
    {
        $codes = Code::with('user')->get();

        return response()->json(['codes' => $codes], 200);
    }
    // public function checkUserCodeStatus($userId,$macAddress)
    // {
    //     $code = Code::where('user_id', $userId)->first();

    //     if (!$code) {
    //         return response()->json(['message' => 'User has no code.'], 404);
    //     }

    //     if (Carbon::now()->greaterThan($code->expires_at)) {
    //         return response()->json(['message' => 'Code has expired.'], 410);
    //     }

    //     if ($code->mac_address!==$macAddress) {
    //         return response()->json(['message' => 'MAC address mismatch.'], 403);
    //     }

    //     return response()->json(['message' => 'User has a valid code.', 'code' => $code], 200);
    // }




    public function checkUserCodeStatus($userId, $macAddress, $lesson_id)
    {
        // Retrieve the code associated with the user and lesson
        $code = Code::where('user_id', $userId)
                    ->where('lesson_id', $lesson_id)
                    ->first();

        if (!$code) {
            return response()->json(['message' => 'No code found for this user and package.'], 404);
        }

        // Check if the code has expired
        if (Carbon::now()->greaterThan($code->expires_at)) {
            return response()->json(['message' => 'Code has expired.'], 410);
        }

        // Normalize the MAC address for comparison
        $normalizedMacAddress = strtolower($macAddress);
        if (strtolower($code->mac_address) !== $normalizedMacAddress) {
            return response()->json(['message' => 'MAC address mismatch.'], 403);
        }

        return response()->json(['message' => 'User has a valid code.', 'code' => $code], 200);
    }





    public function checkUserCodeStatus2($userId, $macAddress2, $lesson_id)
    {
        // Retrieve the code associated with the user and lesson
        $code = Code::where('user_id', $userId)
                    ->where('lesson_id', $lesson_id)
                    ->first();

        if (!$code) {
            return response()->json(['message' => 'No code found for this user and package.'], 404);
        }

        // Check if the code has expired
        if (Carbon::now()->greaterThan($code->expires_at)) {
            return response()->json(['message' => 'Code has expired.'], 410);
        }

        // Normalize the MAC address for comparison
        $normalizedMacAddress = strtolower($macAddress2);
        if (strtolower($code->mac_address2) !== $normalizedMacAddress) {
            return response()->json(['message' => 'MAC address mismatch.'], 403);
        }

        return response()->json(['message' => 'User has a valid code.'], 200);
    }






    public function getUserLessonsWithCode($userId)
{
    // Retrieve codes associated with the user and their lessons
    $codes = Code::where('user_id', $userId)
                ->with('lesson') // Eager load the lesson relationship
                ->get();

    if ($codes->isEmpty()) {
        return response()->json(['message' => 'No codes found for this user.'], 404);
    }

    // Extract lessons and associated code details
    $lessons = $codes->map(function ($code) {
        return [
            'lesson' => $code->lesson, // The lesson details
            'code' => $code->code,     // The code details
            'expires_at' => $code->expires_at, // Expiration date
            'mac_address' => $code->mac_address // MAC address
        ];
    });

    return response()->json(['lessons' => $lessons], 200);


}


}
