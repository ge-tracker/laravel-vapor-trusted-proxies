<?php

namespace GeTracker\LaravelVaporTrustedProxies;

use GeTracker\LaravelVaporTrustedProxies\Http\LaravelVaporTrustedProxies;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelVaporTrustedProxiesServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        // Register a test route to return the requesting IP address
        if ($this->app->runningUnitTests()) {
            Route::get('/test-ip', static fn () => request()->ips())
                ->middleware([LaravelVaporTrustedProxies::class]);
        }
    }
}
