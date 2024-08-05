<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class Sanctum extends EnsureFrontendRequestsAreStateful
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
}
