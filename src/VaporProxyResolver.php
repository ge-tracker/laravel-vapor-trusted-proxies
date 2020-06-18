<?php

namespace GeTracker\LaravelVaporTrustedProxies;

use Illuminate\Support\Arr;

class VaporProxyResolver
{
    private const VAPOR_SOURCE_IP = 'x-vapor-source-ip';
    private const FORWARDED_FOR = 'x-forwarded-for';

    private array $headers;
    private ?array $proxies;

    /**
     * Resolve a new set of trusted proxies
     *
     * @param array      $headers Array of HTTP server headers
     * @param array|null $proxies Array of proxies defined in TrustProxies middleware
     *
     * @return array|null
     */
    public function resolve(array $headers, ?array $proxies = null): ?array
    {
        $this->headers = $headers;
        $this->proxies = $proxies;

        if (!$this->runningInVapor()) {
            return $this->proxies;
        }

        if (($sourceIp = $this->getSourceIp()) && ($clientIps = $this->getClientIps())) {
            $this->addProxies($sourceIp, $clientIps);
        }

        return $this->proxies;
    }

    /**
     * Add proxies starting from the Vapor source IP to the list of trusted proxies
     *
     * @param string $sourceIp
     * @param array  $clientIps
     */
    private function addProxies(string $sourceIp, array $clientIps): void
    {
        $proxies = $this->proxies ?? [];

        $position = array_search($sourceIp, $clientIps, true);

        // Do not modify the list of trusted proxies if the source IP cannot be found in the X-Fwd headers
        // If the sourceIp is returning a 0 position, then it is the last proxy chain in the list and the IP
        // may be spoofed.
        if ($position === false || $position === 0) {
            return;
        }

        // Merge the new list of proxies into the existing set
        $this->proxies = array_merge(
            ['127.0.0.1'], // Always trust localhost
            $proxies,
            array_slice($clientIps, $position)
        );

        // Remove duplicate trusted proxies
        $this->proxies = array_values(array_unique($this->proxies));
    }

    /**
     * Get Vapor source IP
     *
     * @return string|null
     */
    private function getSourceIp(): ?string
    {
        $sourceIp = $this->headers[self::VAPOR_SOURCE_IP] ?? null;

        if (is_array($sourceIp)) {
            return Arr::first($sourceIp);
        }

        return $sourceIp;
    }

    /**
     * Get an array of client IPs (proxies)
     *
     * @return array|null
     */
    private function getClientIps(): ?array
    {
        $forwardedFor = $this->headers[self::FORWARDED_FOR] ?? null;

        if (!$forwardedFor) {
            return null;
        }

        if (!is_array($forwardedFor)) {
            $forwardedFor = [$forwardedFor];
        }

        return array_unique(array_map('trim', explode(',', $forwardedFor[0])));
    }

    private function runningInVapor(): bool
    {
        return isset($_SERVER['VAPOR_ARTIFACT_NAME']);
    }
}
