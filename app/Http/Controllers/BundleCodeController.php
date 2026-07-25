<?php

namespace App\Http\Controllers;

use App\Models\BundleCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BundleCodeController extends Controller
{
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [

        'teacher_bundle_id' => 'required|exists:teacher_bundles,id',

        'expires_at' => 'required|date',

        'count' => 'required|integer|min:1'

    ]);

    if ($validator->fails()) {

        return response()->json($validator->errors(),422);

    }

    $codes=[];

    for($i=0;$i<$request->count;$i++){

        $codes[]=BundleCode::create([

            'teacher_bundle_id'=>$request->teacher_bundle_id,

            'expires_at'=>$request->expires_at

        ]);

    }

    return response()->json([

        'message'=>'Codes created successfully',

        'codes'=>$codes

    ]);

}

public function validateCode(Request $request)
{

    $validator = Validator::make($request->all(), [

        'code'=>'required',

        // 'user_id'=>'required|exists:users,id',

        'teacher_bundle_id'=>'required|exists:teacher_bundles,id',

        'mac_address'=>'required'

    ]);



    if($validator->fails()){

        return response()->json($validator->errors(),422);

    }
$user = auth()->user();

    $code=BundleCode::where('code',$request->code)->first();

    if(!$code){

        return response()->json([
            'message'=>'Invalid Code'
        ],404);

    }

    if(now()->greaterThan($code->expires_at)){

        return response()->json([
            'message'=>'Code Expired'
        ],410);

    }

    if($code->status=="used"){

        return response()->json([
            'message'=>'Code Already Used'
        ],400);

    }


$userId = $user->id;

    $subscription=BundleCode::where('user_id', $user->id)
        ->where('teacher_bundle_id',$request->teacher_bundle_id)
        ->where('status','used')
        ->first();

    if($subscription){

        return response()->json([
            'message'=>'User already subscribed'
        ],400);

    }

    $code->update([

        'user_id' => $user->id,
        'teacher_bundle_id'=>$request->teacher_bundle_id,

        'mac_address'=>$request->mac_address,

        'status'=>'used'

    ]);

    return response()->json([

        'message'=>'Bundle Activated Successfully'

    ]);

}

public function checkUserCodeStatus($mac,$bundleId)
{

$user = auth()->user();

$userId = $user->id;
    $code=BundleCode::where('user_id',$userId)

        ->where('teacher_bundle_id',$bundleId)

        ->where('status','used')

        ->first();

    if(!$code){

        return response()->json([
            'message'=>'No Subscription'
        ],404);

    }

    if(now()->greaterThan($code->expires_at)){

        return response()->json([
            'message'=>'Subscription Expired'
        ],410);

    }

    if(strtolower($code->mac_address)!=strtolower($mac)){

        return response()->json([
            'message'=>'Mac Address Mismatch'
        ],403);

    }

    return response()->json([

        'message'=>'Valid Subscription',

        'bundle'=>$code

    ]);

}

public function index()
{
    return BundleCode::with([
        'user',
        'bundle'
    ])->latest()->get();
}

public function getUserBundles()
{
$user = auth()->user();

$userId = $user->id;
    return BundleCode::with('bundle')

        ->where('user_id',$userId)

        ->where('status','used')

        ->get();

}
}
