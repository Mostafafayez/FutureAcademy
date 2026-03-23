<?php

namespace App\Providers;

use App\Exceptions\ApiAuthException;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

use Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
public function boot()
{
    Relation::morphMap([
        'package' => \App\Models\Packages::class,
        'teacher' => \App\Models\Teacher::class,
        'lesson'  => \App\Models\Lesson::class,
    ]);
}
}
