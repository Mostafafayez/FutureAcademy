<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CodeV2Controller extends Controller
{
    public function validateCode(Request $request)
{
    $validator = Validator::make($request->all(), [
        'code' => 'required|string',
        'mac_address' => 'nullable|string|max:255',
        'user_id' => 'required|exists:users,id',

        'type' => 'required|in:teacher,package,bundle',

        // 'teacher_id' => 'nullable|exists:teachers,id',
        'lesson_id' => 'nullable|exists:packages,id',
        'teacher_bundle_id' => 'nullable|exists:teacher_bundles,id',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    $code = Code::where('code', $request->code)->first();

    if (!$code) {
        return response()->json([
            'message' => 'Invalid code.'
        ],404);
    }

    if (Carbon::now()->greaterThan($code->expires_at)) {

        $code->status = 'expired';
        $code->save();

        return response()->json([
            'message'=>'Code has expired.'
        ],410);
    }

    if ($code->status != 'notused') {

        return response()->json([
            'message'=>'Code is already used.'
        ],400);
    }

    // Check duplicate subscription
    $existing = Code::where('user_id',$request->user_id)
        ->where('type',$request->type)
        ->where('status','active');

    // if($request->type == 'teacher'){
    //     $existing->where('teacher_id',$request->teacher_id);
    // }

    if($request->type == 'package'){
        $existing->where('lesson_id',$request->lesson_id);
    }

    if($request->type == 'bundle'){
        $existing->where('teacher_bundle_id',$request->teacher_bundle_id);
    }

    if($existing->exists()){

        return response()->json([
            'message'=>'User already subscribed.'
        ],400);

    }

    $code->user_id = $request->user_id;
    $code->type = $request->type;
    // $code->teacher_id = $request->teacher_id;
    $code->lesson_id = $request->lesson_id;
    $code->teacher_bundle_id = $request->teacher_bundle_id;
    $code->mac_address = $request->mac_address;

    $code->status = 'active';

    $code->save();

    return response()->json([
        'message'=>'Code activated successfully.'
    ],200);
}




public function checkUserCodeStatus(
    $userId,
    $macAddress,
    $type,
    // $teacherId = null,
    $lessonId = null,
    $bundleId = null
)
{
    $query = Code::where('user_id',$userId)
                ->where('type',$type)
                ->where('status','active');

    switch ($type){

        // case 'teacher':

        //     $query->where('teacher_id',$teacherId);

        //     break;

        case 'package':

            $query->where('lesson_id',$lessonId);

            break;

        case 'bundle':

            $query->where('teacher_bundle_id',$bundleId);

            break;
    }

    $code = $query->first();

    if(!$code){

        return response()->json([
            'message'=>'No valid subscription found.'
        ],404);

    }

    if(Carbon::now()->greaterThan($code->expires_at)){

        $code->status='expired';
        $code->save();

        return response()->json([
            'message'=>'Code expired.'
        ],410);

    }

    if(
        $code->mac_address &&
        strtolower($code->mac_address)!=strtolower($macAddress)
    ){

        return response()->json([
            'message'=>'MAC address mismatch.'
        ],403);

    }

    return response()->json([
        'message'=>'Access approved.',
        'code'=>$code
    ],200);
}

}
