<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
// use Illuminate\Validation\ValidationException;


public function render($request, Throwable $exception)
{
    // لو الصفحة مش موجودة
    if ($exception instanceof NotFoundHttpException) {
        return response()->json([
            'status' => false,
            'message' => 'Page not found'
        ], 404);
    }

    // لو أي خطأ تاني (500)
    return response()->json([
        'status' => false,
        'message' => 'Something went wrong'
    ], 500);
}
}
