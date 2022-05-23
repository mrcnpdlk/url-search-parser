# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [v2.7.0] - 2022-05-23
### Added:
- `removeByParamName` method in Sort object
### Changed:
- PhpCsFixer update
## [v2.6.0] - 2020-09-30
### Added:
- `removeByParam` method
## [v2.5.1] - 2020-09-30
### Changed:
- `isRegexp` -> `isWhereRegexp`
- doc
## [v2.5.0] - 2020-09-30
### Added:
- `rlike`, `llike`, `regexp` filters
## [v2.4.0] - 2020-03-19
### Changed:
- composer up
### Added:
- rawurldecode()
## [v2.3.0] - 2019-11-25
### Changed
- empty phrase means NULL
## [v2.2.0] - 2019-11-03
### Added:
- method `getQuery` - issue [[Feature Request] support http_build_query](https://github.com/mrcnpdlk/url-search-parser/issues/8)
- method `setQueryParam`
### Fixed
- removing specified query param will throw an InvalidParamException

## [v2.1.0] - 2019-09-06
### Added
- FOSSA check
- phpstan warnings fix
- throw `DuplicateParamException` for duplicate sort param
- test, PHPUnit 8.0
## [v2.0.0] - 2019-03-29
### Changed
- namespace change `mrcnpdlk`->`Mrcnpdlk`
### Added
- operator: `not` for filters
## [v1.5.0] - 2019-01-29
### Added:
- setters
## [v1.4.0] - 2019-01-18
### Added
- new method: `replaceParam()`
- tests
## [v1.3.0] - 2019-01-17
### Added
- new method: `getByParam()`
### Changed
- tests
## [v1.2.0] - 2018-10-05
### Added
- new method: `getQuery()`, `getQueryHash()`
- README
## [v1.1.3] - 2018-09-31
### Added
- null & notnull operator support
- BOOLEAN support 
## [v1.1.2] - 2018-07-20
### Added
- new method: `removeQueryParam()`
## [v1.1.1] - 2018-06-17
### Changed
- `getQueryParam()`: type can be NULL, convert AS IS
## [v1.1.0] - 2018-06-17
### Added
- new method: `getQueryParam()`
## [v1.0.0] - 2018-06-17
### Added
- new params support: `like`
- CI integration
- namespace change
- tests
## [v0.2.0] - 2018-06-16
### Added
- new params support: `phrase`,`page`,`offset`
## [v0.1.2] - 2018-06-16
### Fixed
- filter params can be empty array
## [v0.1.1] - 2018-06-16
### Fixed
- `print_r`

## [0.1.0] - 2018-06-16

[Unreleased]: https://github.com/mrcnpdlk/url-search-parser/compare/v2.3.0...devel
[v2.3.0]: https://github.com/mrcnpdlk/url-search-parser/compare/v2.2.0...v2.3.0
[v2.2.0]: https://github.com/mrcnpdlk/url-search-parser/compare/v2.1.0...v2.2.0
[v2.1.0]: https://github.com/mrcnpdlk/url-search-parser/compare/v2.0.0...v2.1.0
[v2.0.0]: https://github.com/mrcnpdlk/url-search-parser/compare/v1.5.0...v2.0.0
[v1.5.0]: https://github.com/mrcnpdlk/url-search-parser/compare/v1.4.0...v1.5.0
[v1.4.0]: https://github.com/mrcnpdlk/url-search-parser/compare/v1.3.0...v1.4.0
[v1.3.0]: https://github.com/mrcnpdlk/url-search-parser/compare/v1.2.0...v1.3.0
[v1.2.0]: https://github.com/mrcnpdlk/url-search-parser/compare/v1.1.3...v1.2.0
[v1.1.3]: https://github.com/mrcnpdlk/url-search-parser/compare/v1.1.2...v1.1.3
[v1.1.2]: https://github.com/mrcnpdlk/url-search-parser/compare/v1.1.1...v1.1.2
[v1.1.1]: https://github.com/mrcnpdlk/url-search-parser/compare/v1.1.0...v1.1.1
[v1.1.0]: https://github.com/mrcnpdlk/url-search-parser/compare/v1.0.0...v1.1.0
[v1.0.0]: https://github.com/mrcnpdlk/url-search-parser/compare/v0.2.0...v1.0.0
[v0.2.0]: https://github.com/mrcnpdlk/url-search-parser/compare/v0.1.2...v0.2.0
[v0.1.2]: https://github.com/mrcnpdlk/url-search-parser/compare/v0.1.1...v0.1.2
[v0.1.1]: https://github.com/mrcnpdlk/url-search-parser/compare/v0.1.0...v0.1.1
[v0.1.0]: https://github.com/mrcnpdlk/url-search-parser/releases/tag/v0.1.0
