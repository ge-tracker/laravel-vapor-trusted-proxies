<?php

namespace GeTracker\LaravelVaporTrustedProxies;

use Illuminate\Support\Facades\Facade;

/**
 * @see \GeTracker\LaravelVaporTrustedProxies\Skeleton\SkeletonClass
 */
class LaravelVaporTrustedProxiesFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-vapor-trusted-proxies';
    }
}
