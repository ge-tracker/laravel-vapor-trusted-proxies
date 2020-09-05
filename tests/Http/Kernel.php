<?php

namespace GeTracker\LaravelVaporTrustedProxies\Tests\Http;

class Kernel extends \Orchestra\Testbench\Http\Kernel
{
    /**
     * The application's middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Orchestra\Testbench\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

        /**
         * Register the custom middleware
         */
        \GeTracker\LaravelVaporTrustedProxies\Http\LaravelVaporTrustedProxies::class,
    ];
}
