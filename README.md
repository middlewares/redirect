# middlewares/redirect

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]

Middleware to redirect old urls to new urls SEO friendly.

## Requirements

* PHP >= 7.2
* A [PSR-7 http library](https://github.com/middlewares/awesome-psr15-middlewares#psr-7-implementations)
* A [PSR-15 middleware dispatcher](https://github.com/middlewares/awesome-psr15-middlewares#dispatcher)

## Installation

This package is installable and autoloadable via Composer as [middlewares/redirect](https://packagist.org/packages/middlewares/redirect).

```sh
composer require middlewares/redirect
```

## Example

```php
Dispatcher::run([
	new Middlewares\Redirect(['/old-url' => '/new-url'])
]);
```

## Usage

You can use an array or an object extending `ArrayAccess` interface with the urls to redirect, the key is the old url and the value the new.

```php
$redirections = [
	'/corporative-info' => '/about-us',
	'/post/2390' => '/post/new-psr15-middlewares',
];

$redirect = new Middlewares\Redirect($redirections);
```

Optionally, you can provide a `Psr\Http\Message\ResponseFactoryInterface` as the second argument to create the redirect responses. If it's not defined, [Middleware\Utils\Factory](https://github.com/middlewares/utils#factory) will be used to detect it automatically.

```php
$responseFactory = new MyOwnResponseFactory();

$redirect = new Middlewares\Redirect($redirections, $responseFactory);
```

### permanent

Use temporary or permanent redirection HTTP status code for the response. Enabled by default.

```php
//Temporary redirections (302)
$redirect = (new Middlewares\Redirect($redirections))->permanent(false);
```

### query

Take the query part of the URI into account when matching redirects. Enabled by default.

```php
//Ignore url query
$redirect = (new Middlewares\Redirect($redirections))->query(false);
```

### method

This option accepts an array with the allowed HTTP request methods. (By default is: `['GET']`.)

```php
//Redirects GET and HEAD requests
$redirect = (new Middlewares\Redirect($redirections))->method(['GET', 'HEAD']);
```

---

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes and [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/middlewares/redirect.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/middlewares/redirect/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/g/middlewares/redirect.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/middlewares/redirect.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/middlewares/redirect
[link-travis]: https://travis-ci.org/middlewares/redirect
[link-scrutinizer]: https://scrutinizer-ci.com/g/middlewares/redirect
[link-downloads]: https://packagist.org/packages/middlewares/redirect
