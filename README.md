# Laravel Vapor Trusted Proxies

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ge-tracker/laravel-vapor-trusted-proxies.svg?style=flat-square)](https://packagist.org/packages/ge-tracker/laravel-vapor-trusted-proxies)
[![Total Downloads](https://img.shields.io/packagist/dt/ge-tracker/laravel-vapor-trusted-proxies.svg?style=flat-square)](https://packagist.org/packages/ge-tracker/laravel-vapor-trusted-proxies)

This package was created due to `request()->ip()` always returning `127.0.0.1` on  [Laravel Vapor](https://vapor.laravel.com/). There are [several fixes](https://stackoverflow.com/questions/58346824/how-can-i-get-the-ip-of-an-http-request-in-a-laravel-vapor-application/) online that [trust all proxy servers](https://github.com/fideloper/TrustedProxy/issues/107#issuecomment-373065215). These solutions may be suitable for basic applications, however, these changes will allow any user to send the `X-FORWARDED-FOR` header to spoof their originating IP address.

Due to the dynamic nature of Laravel Vapor, it becomes a challenge to set the [trusted proxies](https://laravel.com/docs/7.x/requests#configuring-trusted-proxies) for your Laravel application. If you rely on the IP address of the user being valid, then this package is for you!

## Version Compatibility

[Laravel 9.0](https://laravel.com/docs/9.x/upgrade) introduced changes to the default `TrustedProxies` middleware, and the [fideloper/proxy](https://packagist.org/packages/fideloper/proxy) package is no longer required, as the [functionality is included with Laravel](https://github.com/illuminate/http/blob/9.x/Middleware/TrustProxies.php). I'm not actively using Vapor and am not sure whether this package is still required, but I have gone ahead and updated the requirements and pushed the `v2.0` release, which drops support for earlier Laravel versions.

| Laravel  | Package Version |
| -------- | --------------- |
| `^9.0`   | `^2.0`          |
| `<= 9.0` | `^1.0`          |

## Installation

You can install the package via composer:

```bash
composer require ge-tracker/laravel-vapor-trusted-proxies
```

Next, you must edit your `app\Http\Middleware\TrustProxies.php` middleware and modify an import to use the middleware provided by this package:

```php
<?php

namespace App\Http\Middleware;

use GeTracker\LaravelVaporTrustedProxies\Http\LaravelVaporTrustedProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    ...
```

The package will then work out-of-the-box to dynamically fetch the proxy servers used by your current Vapor's deployment.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email james@ge-tracker.com instead of using the issue tracker.

## Credits

- [GE Tracker](https://github.com/ge-tracker)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
