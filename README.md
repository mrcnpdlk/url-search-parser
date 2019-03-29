# mrcnpdlk/url-search-parser
[![Latest Stable Version](https://img.shields.io/github/release/mrcnpdlk/url-search-parser.svg)](https://packagist.org/packages/mrcnpdlk/url-search-parser)
[![Latest Unstable Version](https://poser.pugx.org/mrcnpdlk/url-search-parser/v/unstable.png)](https://packagist.org/packages/mrcnpdlk/url-search-parser)
[![Total Downloads](https://img.shields.io/packagist/dt/mrcnpdlk/url-search-parser.svg)](https://packagist.org/packages/mrcnpdlk/url-search-parser)
[![Monthly Downloads](https://img.shields.io/packagist/dm/mrcnpdlk/url-search-parser.svg)](https://packagist.org/packages/mrcnpdlk/url-search-parser)
[![License](https://img.shields.io/packagist/l/mrcnpdlk/url-search-parser.svg)](https://packagist.org/packages/mrcnpdlk/url-search-parser)

------

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mrcnpdlk/url-search-parser/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mrcnpdlk/url-search-parser/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/mrcnpdlk/url-search-parser/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mrcnpdlk/url-search-parser/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/mrcnpdlk/url-search-parser/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mrcnpdlk/url-search-parser/build-status/master)

[![Code Climate](https://codeclimate.com/github/mrcnpdlk/url-search-parser/badges/gpa.svg)](https://codeclimate.com/github/mrcnpdlk/url-search-parser) 
[![Issue Count](https://codeclimate.com/github/mrcnpdlk/url-search-parser/badges/issue_count.svg)](https://codeclimate.com/github/mrcnpdlk/url-search-parser)


[![Build Status](https://travis-ci.com/mrcnpdlk/url-search-parser.svg?branch=master)](https://travis-ci.com/mrcnpdlk/url-search-parser)

# Contents

This boundle has been created for parsing advanced queries to easy-to-use objects. 

Based on https://www.vinaysahni.com/best-practices-for-a-pragmatic-restful-api#advanced-queries

1. [Installation](#installation)
2. [Supported parameters](#supported-parameters)
   1. [Sort](#sort)
   2. [Filter](#filter)
   3. [Limit](#limit)
   4. [Page](#page)
   5. [Phrase](#phrase)
   6. [Others](#other-params)
3. [Usage](#usage)


## Installation

Install the latest version with [composer](https://packagist.org/packages/mrcnpdlk/teryt-api)
```bash
composer require mrcnpdlk/url-search-parser
```
## Supported parameters

```php
$oParser = new \Mrcnpdlk\Lib\UrlQueryParser\RequestParser($query); 
```

### Sort

Generic parameter `sort` can be used to describe sorting rules. Accommodate complex sorting requirements by letting the sort parameter take in list of comma separated fields, each with a possible unary negative to imply descending sort order. Let's look at some examples: 

- `GET /messages?sort=-createDate` - Retrieves a list of messages in descending order of createDate;
- `GET /messages?sort=-type,createDate` - Retrieves a list of messages in descending order of type. Within a specific type, older messages are ordered first;

```php
/**
 * @var $oSort \Mrcnpdlk\Lib\UrlQueryParser\Criteria\Sort
 */
$oSort = $oParser->getSort();
```

### Filter

Filtering is more complex then sorting. Array notation is used. Let's look at some examples: 

- `GET /messages?filter[created][lt]=2018-06-01` - Retrieves a list of messages where createDate is lower than 2018-06-01;
- `GET /messages?filter[type][in]=urgent,warning,error` - Retrieves a list of messages where type is urgent, warning or error;
- `GET /messages?filter[type][null]` - Retrieves a list of messages where type is NULL;

Allowed operators: `eq`,`lt`,`lte`,`gt`,`gte`,`like`,`in`,`notin`,`null`,`notnull`

In case using not allowed operator `mrcnpdlk\Lib\UrlQueryParser\Exception\InvalidParamException` is thrown.

```php
/**
 * @var $oFilter \Mrcnpdlk\Lib\UrlQueryParser\Criteria\Filter
 */
$oFilter = $oParser->getFilter();
```

### Limit

Example:

- `GET /messages?limit=20` - limitation;

```php
/**
 * @var $iLimit integer|null
 */
$iLimit = $oParser->getLimit(10); // in NULL default=10
```

### Page

Example:

- `GET /messages?page=1` - pagination. Should be used with `limit` parameter;

```php
/**
 * @var $iPage integer|null
 */
$iPage = $oParser->getPage(1); // in NULL default=1
```

### Phrase

Example:

- `GET /messages?phrase=foo` - for easier filtering;

```php
/**
 * @var $sPhrase string|null
 */
$sPhrase = $oParser->getPhrase(); // if not set NULL ir returned
```

### Other params

Use `getQueryParam()` method.

Example:

- `GET /messages?foo=bar&baz=5` - additional params;

```php
$sFoo = $oParser->getQueryParam('foo','string'); // return 'bar'
$sBaz = $oParser->getQueryParam('baz','int'); // return 5
```



## Usage

```php
// Two ways to get `query` argument for RequestParser constructor:
$query = parse_url($url, PHP_URL_QUERY); // OR
$query = $_SERVER['QUERY_STRING'];

$oParser = new \Mrcnpdlk\Lib\UrlQueryParser\RequestParser($query); 
```



### Example

```php
$url = 'https://api.expample.com?sort=id,-name&filter[isFoo][eq]=1&filter[age][gt]=12&page=3&limit=10&offset=20';
$query =  parse_url($url, PHP_URL_QUERY);
$oParser = new \Mrcnpdlk\Lib\UrlQueryParser\RequestParser($query);

print_r($oParser->getSort()->toArray());
print_r($oParser->getFilter()->toArray());
print_r($oParser->getLimit());
print_r($oParser->getPage());
print_r($oParser->getPhrase());
```

Result

```cassandra
Array
(
    [0] => mrcnpdlk\Lib\UrlQueryParser\Criteria\SortParam Object
        (
            [param] => id
            [direction] => ASC
        )

    [1] => mrcnpdlk\Lib\UrlQueryParser\Criteria\SortParam Object
        (
            [param] => name
            [direction] => DESC
        )

)
Array
(
    [0] => mrcnpdlk\Lib\UrlQueryParser\Criteria\FilterParam Object
        (
            [param] => isFoo
            [operator] => eq
            [sqlOperator] => =
            [value] => 1
        )

    [1] => mrcnpdlk\Lib\UrlQueryParser\Criteria\FilterParam Object
        (
            [param] => age
            [operator] => gt
            [sqlOperator] => >
            [value] => 12
        )

)
10 // limit
3  // page
20 // offset
```



## Running the tests

```bash
./vendor/bin/phpunit
```

## Authors

* **Marcin Pudełek** - *Initial work* - [mrcnpdlk](https://github.com/mrcnpdlk)

See also the list of [contributors](https://github.com/mrcnpdlk/url-search-parser/graphs/contributors) who participated in this project.

## License

Copyright (c) 2018 Marcin Pudełek / mrcnpdlk

This project is licensed under the MIT License - see the [LICENSE](https://github.com/mrcnpdlk/url-search-parser/blob/master/LICENSE) file for details
