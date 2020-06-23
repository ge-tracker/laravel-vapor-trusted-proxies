<?php

namespace GeTracker\LaravelVaporTrustedProxies\Tests;

use GeTracker\LaravelVaporTrustedProxies\LaravelVaporTrustedProxiesServiceProvider;
use GeTracker\LaravelVaporTrustedProxies\VaporProxyResolver;
use Orchestra\Testbench\TestCase;

class VaporProxyResolverTest extends TestCase
{
    private VaporProxyResolver $proxyResolver;

    protected function getPackageProviders($app)
    {
        return [LaravelVaporTrustedProxiesServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->proxyResolver = new VaporProxyResolver();
        $_SERVER['VAPOR_ARTIFACT_NAME'] = 'laravel-vapor';
    }

    /** @test */
    public function only_runs_in_vapor_environment(): void
    {
        unset($_SERVER['VAPOR_ARTIFACT_NAME']);

        $proxies = $this->proxyResolver->resolve([
            'x-vapor-source-ip' => '2.2.2.2',
            'x-forwarded-for'   => '1.2.3.4, 2.2.2.2, 3.3.3.3',
        ], ['127.0.0.1']);

        $this->assertCount(1, $proxies);
        $this->assertSame('127.0.0.1', $proxies[0]);
    }

    /** @test */
    public function adds_the_two_proxy_headers_with_default_proxy(): void
    {
        $proxies = $this->proxyResolver->resolve([
            'x-vapor-source-ip' => '2.2.2.2',
            'x-forwarded-for'   => '1.2.3.4, 2.2.2.2, 3.3.3.3',
        ], ['127.0.0.1']);

        $this->assertCount(3, $proxies);
        $this->assertSame('127.0.0.1', $proxies[0]);
        $this->assertSame('2.2.2.2', $proxies[1]);
        $this->assertSame('3.3.3.3', $proxies[2]);
    }

    /** @test */
    public function does_not_fail_on_array_headers(): void
    {
        $proxies = $this->proxyResolver->resolve([
            'x-vapor-source-ip' => ['2.2.2.2'],
            'x-forwarded-for'   => ['1.2.3.4, 2.2.2.2, 3.3.3.3'],
        ], ['127.0.0.1']);

        $this->assertCount(3, $proxies);
    }

    /** @test */
    public function does_not_fail_on_string_proxy(): void
    {
        $proxies = $this->proxyResolver->resolve([
            'x-vapor-source-ip' => ['2.2.2.2'],
            'x-forwarded-for'   => ['1.2.3.4, 2.2.2.2, 3.3.3.3'],
        ], '*');

        $this->assertCount(4, $proxies);
    }

    /** @test */
    public function adds_the_two_proxy_headers_with_no_defaults(): void
    {
        $proxies = $this->proxyResolver->resolve([
            'x-vapor-source-ip' => '2.2.2.2',
            'x-forwarded-for'   => '1.2.3.4, 2.2.2.2, 3.3.3.3',
        ]);

        $this->assertCount(3, $proxies);
        $this->assertSame('127.0.0.1', $proxies[0]);
        $this->assertSame('2.2.2.2', $proxies[1]);
        $this->assertSame('3.3.3.3', $proxies[2]);
    }

    /** @test */
    public function stop_basic_spoof_with_no_proxies_configured(): void
    {
        $proxies = $this->proxyResolver->resolve([
            'x-vapor-source-ip' => '1.2.3.4',
            'x-forwarded-for'   => '1.2.3.4, 2.2.2.2, 3.3.3.3',
        ]);

        $this->assertNull($proxies);
    }

    /** @test */
    public function stop_basic_spoof_with_proxies_configured(): void
    {
        $proxies = $this->proxyResolver->resolve([
            'x-vapor-source-ip' => '1.2.3.4',
            'x-forwarded-for'   => '1.2.3.4, 2.2.2.2, 3.3.3.3',
        ], ['127.0.0.1', '10.0.2.21']);

        $this->assertCount(2, $proxies);
        $this->assertSame('127.0.0.1', $proxies[0]);
        $this->assertSame('10.0.2.21', $proxies[1]);
    }
}
