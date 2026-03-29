<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;




class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */

public function boot()
{
    $this->configureRateLimiting();

    $this->routes(function () {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    });
}

protected function configureRateLimiting()
{
    RateLimiter::for('api', function (Request $request) {
        // لو user مسجل
        if ($request->user()) {
            // مثال: Teachers عندهم limit أعلى
            if ($request->user() instanceof \App\Models\Teacher) {
                return Limit::perMinute(1)->by($request->user()->id);
            }

            // باقي الطلاب
            return Limit::perMinute(60)->by($request->user()->id);
        }

        // لو مش مسجل (guest)
        return Limit::perMinute(30)->by($request->ip());
    });
}
}
