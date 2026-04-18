<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use Illuminate\Support\Facades\File;
class LogController extends Controller
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




public function clearLogs()
{
    $path = storage_path('logs/laravel.log');

    if (!File::exists($path)) {
        return response()->json([
            'message' => 'Log file not found'
        ], 404);
    }

    File::put($path, '');

    return response()->json([
        'message' => 'Logs cleared successfully'
    ], 200);
}
}
