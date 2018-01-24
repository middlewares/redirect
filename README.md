# middlewares/redirect

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
[![SensioLabs Insight][ico-sensiolabs]][link-sensiolabs]

Middleware to redirect old urls to new urls SEO friendly.

## Requirements

* PHP >= 7.0
* A [PSR-7](https://packagist.org/providers/psr/http-message-implementation) http message implementation ([Diactoros](https://github.com/zendframework/zend-diactoros), [Guzzle](https://github.com/guzzle/psr7), [Slim](https://github.com/slimphp/Slim), etc...)
* A [PSR-15 middleware dispatcher](https://github.com/middlewares/awesome-psr15-middlewares#dispatcher)

## Installation

This package is installable and autoloadable via Composer as [middlewares/redirect](https://packagist.org/packages/middlewares/redirect).

```sh
composer require middlewares/redirect
```

## Example

```php
$dispatcher = new Dispatcher([
	(new Middlewares\Redirect(['/old-url' => '/new-url']))
		->permanent(false)
		->query(false)
		->method(['GET', 'POST'])
]);

$response = $dispatcher->dispatch(new ServerRequest());
```

## Options

#### `__construct(array|ArrayAccess $redirects)`

The list of urls that must be redirected. It can be an array or an object implementing the `ArrayAccess` interface.

#### `permanent(bool $permanent)`

Use temporary or permanent redirection HTTP status code for the response. (Default: `true`.)

#### `query(bool $query)`

Take the query part of the URI into account when matching redirects. (Default: `true`.)

#### `method(array $methods)`

Array with allow HTTP request methods. (Default: `['GET']`.)

---

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes and [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/middlewares/redirect.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/middlewares/redirect/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/g/middlewares/redirect.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/middlewares/redirect.svg?style=flat-square
[ico-sensiolabs]: https://img.shields.io/sensiolabs/i/{project_id_here}.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/middlewares/redirect
[link-travis]: https://travis-ci.org/middlewares/redirect
[link-scrutinizer]: https://scrutinizer-ci.com/g/middlewares/redirect
[link-downloads]: https://packagist.org/packages/middlewares/redirect
[link-sensiolabs]: https://insight.sensiolabs.com/projects/e2df0b3f-ee64-4310-91e6-f7e53f024808
