<?php

namespace GeTracker\LaravelVaporTrustedProxies\Http;

use GeTracker\LaravelVaporTrustedProxies\VaporProxyResolver;
use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class LaravelVaporTrustedProxies extends Middleware
{
    public function __construct(
        private VaporProxyResolver $proxyResolver
    ) {
    }

    /**
     * Override the base TrustProxies middleware
     *
     * @param Request $request
     *
     * @noinspection PhpVoidFunctionResultUsedInspection
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    protected function setTrustedProxyIpAddresses(Request $request)
    {
        $this->proxies = $this->proxyResolver->resolve(
            $request->headers->all(),
            $this->proxies
        );

        return parent::setTrustedProxyIpAddresses($request);
    }
}
