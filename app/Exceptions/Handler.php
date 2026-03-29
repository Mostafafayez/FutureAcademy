<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Validation\ValidationException;

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
    // تحقق من ValidationException
    if ($exception instanceof ValidationException) {
        return response()->json([
            'status' => false,
            'message' => 'يوجد بيانات غير صحيحة، يرجى التحقق من المدخلات'
        ], 422);
    }

    // AuthenticationException لتوكن منتهي
    if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
        return response()->json([
            'status' => false,
            'message' => 'تم انتهاء صلاحية التوكن، يرجى تسجيل الدخول مرة أخرى'
        ], 401);
    }

    // Throttle requests
    if ($exception instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
        return response()->json([
            'status' => false,
            'message' => 'عدد الطلبات كبير جدًا، حاول بعد دقيقة'
        ], 429);
    }

    return parent::render($request, $exception);
}
}
