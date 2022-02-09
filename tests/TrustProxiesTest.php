<?php

namespace GeTracker\LaravelVaporTrustedProxies\Tests;

use GeTracker\LaravelVaporTrustedProxies\LaravelVaporTrustedProxiesServiceProvider;
use Orchestra\Testbench\TestCase;

class TrustProxiesTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [LaravelVaporTrustedProxiesServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $_SERVER['VAPOR_ARTIFACT_NAME'] = 'laravel-vapor';
    }

    /** @test */
    public function returns_localhost_when_not_running_in_vapor(): void
    {
        // Remove Vapor server variable
        unset($_SERVER['VAPOR_ARTIFACT_NAME']);

        $response = $this->get('/test-ip', [
            'X-VAPOR-SOURCE-IP' => '2.2.2.2',
            'X-FORWARDED-FOR'   => '1.2.3.4, 2.2.2.2, 3.3.3.3',
        ]);

        $response->assertStatus(200)
            ->assertJson(['127.0.0.1']);
    }

    /** @test */
    public function returns_actual_user_ip_by_resolving_vapor_proxies(): void
    {
        $response = $this->get('/test-ip', [
            'REMOTE_ADDR'       => '127.0.0.1',
            'X-VAPOR-SOURCE-IP' => '2.2.2.2',
            'X-FORWARDED-FOR'   => '1.2.3.4, 2.2.2.2, 3.3.3.3',
        ]);

        $response->assertStatus(200)
            ->assertJson(['1.2.3.4']);
    }
}
