<?php

namespace GeTracker\LaravelVaporTrustedProxies\Http;

use Fideloper\Proxy\TrustProxies as Middleware;
use GeTracker\LaravelVaporTrustedProxies\VaporProxyResolver;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;

class LaravelVaporTrustedProxies extends Middleware
{
    private VaporProxyResolver $proxyResolver;

    public function __construct(Repository $config, VaporProxyResolver $proxyResolver)
    {
        parent::__construct($config);

        $this->proxyResolver = $proxyResolver;
    }

    /**
     * Override the base TrustProxies middleware
     *
     * @param Request $request
     *
     * @noinspection PhpVoidFunctionResultUsedInspection
     */
    protected function setTrustedProxyIpAddresses(Request $request)
    {
        $trustedIps = $this->proxies ?: $this->config->get('trustedproxy.proxies');

        $this->proxies = $this->proxyResolver->resolve(
            $request->headers->all(),
            $trustedIps
        );

        return parent::setTrustedProxyIpAddresses($request);
    }
}
