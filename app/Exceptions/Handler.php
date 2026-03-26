<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // ✅ هنا الصح (برا register)
    public function render($request, Throwable $exception)
    {
        // if ($request->expectsJson()) {
        //                 return response()->json([
        //             'status' => false,
        //             'message' => 'Something went wrong'
        //         ], 500);
        // }

        return parent::render($request, $exception);
    }
}
