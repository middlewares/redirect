# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [2.1.0] - 2025-03-21
### Added
- Support for PHP 8.4

## [2.0.2] - 2024-07-11
### Fixed
- Allow `middlewares/utils:^4` [#4]

## [2.0.1] - 2020-12-02
### Added
- Support for PHP 8

## [2.0.0] - 2019-12-06
### Added
- Added a second argument to the constructor to set a `ResponseFactoryInterface`

### Removed
- Support for PHP 7.0 and 7.1
- The `responseFactory()` option. Use the `__construct` argument.

## [1.1.0] - 2018-08-04
### Added
- PSR-17 support
- New option `responseFactory`

## [1.0.0] - 2018-01-27
### Added
- Improved testing and added code coverage reporting
- Added tests for PHP 7.2

### Changed
- Upgraded to the final version of PSR-15 `psr/http-server-middleware`

### Fixed
- Updated license year

## [0.3.1] - 2018-01-07
### Fixed
- Moved `middlewares/utils` from require-dev to require.

## [0.3.0] - 2017-11-13
### Changed
- Replaced `http-interop/http-middleware` with  `http-interop/http-server-middleware`.

### Removed
- Removed support for PHP 5.x.

## [0.2.0] - 2017-09-21
### Changed
- Updated to `http-interop/http-middleware#0.5`

## [0.1.0] - 2017-09-19
First version

[#4]: https://github.com/middlewares/redirect/issues/4

[2.1.0]: https://github.com/middlewares/redirect/compare/v2.0.2...v2.1.0
[2.0.2]: https://github.com/middlewares/redirect/compare/v2.0.1...v2.0.2
[2.0.1]: https://github.com/middlewares/redirect/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/middlewares/redirect/compare/v1.1.0...v2.0.0
[1.1.0]: https://github.com/middlewares/redirect/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/middlewares/redirect/compare/v0.3.1...v1.0.0
[0.3.1]: https://github.com/middlewares/redirect/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/middlewares/redirect/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/middlewares/redirect/compare/v0.1.0...v0.2.0
[0.1.0]: https://github.com/middlewares/redirect/releases/tag/v0.1.0
