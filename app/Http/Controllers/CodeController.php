<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class CodeController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expires_at' => 'required|date',
            'access_type' => 'required|in:teacher,subject',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        // Create the new code
        $code = Code::create([
            'mac_address' => '', // Initialize with an empty string
            // 'user_id' => '0',
            'expires_at' => $request->expires_at,
            'access_type' => $request->access_type,
        ]);

        return response()->json(['message' => 'Code created successfully', 'code' => $code], 201);
    }

    public function validateCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'mac_address' => 'string|max:255|:codes,mac_address',
            'teacher_name' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $code = Code::where('code', $request->code)->first();

        if (!$code) {
            return response()->json(['message' => 'Invalid code or access type.'], 404);
        }

        if (Carbon::now()->greaterThan($code->expires_at)) {
            return response()->json(['message' => 'Code has expired.'], 410);
        }

        if ($code->type === 'used') {
            return response()->json(['message' => 'Code is already used.'], 400);
        }

        // If the code type is 'notused', update the fields and set type to 'used'
        if ($code->type === 'notused') {
            $code->user_id = $request->user_id;
            $code->mac_address = $request->mac_address;
            $code->teacher_name = $request->teacher_name;
            $code->type = 'used';
            $code->save();

            return response()->json(['message' => 'Code validated and updated successfully.'], 200);
        }

        return response()->json(['message' => 'Unknown error occurred.'], 500);
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






public function checkUserCodeStatus($userId, $macAddress)
{
    $code = Code::where('user_id', $userId)->first();

    if (!$code) {
        return response()->json(['message' => 'User has no code.'], 404);
    }

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

}
