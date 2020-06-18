<?php

namespace GeTracker\LaravelVaporTrustedProxies;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelVaporTrustedProxiesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Register a test route to return the requesting IP address
        if ($this->app->runningUnitTests()) {
            Route::get('/test-ip', function () {
                return request()->ips();
            });
        }
    }
}
