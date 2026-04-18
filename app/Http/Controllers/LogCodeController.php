<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use Illuminate\Support\Facades\File;
class LogCodeController extends Controller
{


public function getLogs()
{
    $path = storage_path('logs/laravel.log');

    if (!File::exists($path)) {
        return response()->json(['message' => 'Log file not found'], 404);
    }

    $content = File::get($path);

    return response()->json([
        'logs' => $content
    ]);
}
}
