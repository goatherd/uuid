Pure PHP UUID generator
=======================

Dual-licensed under BSDL (2-clause) or Apache 2.0 license 

Derived from code by [Fredrik Lindberg](https://github.com/fredriklindberg).

Features
--------

Optimised for use with PHP 5.3+ and [psr](https://github.com/php-fig/fig-standards/tree/master/accepted)-2 compliant.

* Composer support ([packagist](https://packagist.org/packages/goatherd/goatherd-library-uuid))
* format as `FMT_STRING`, `FMT_BYTE` or `FMT_BINARY`
* psr-0 autoloading
* UUID version 1 (time), 3 (md5), 4 (random) and 5 (sha1)
* generates same UUIDs for little and big endian architecture
* [UnitTest](http://www.phpunit.de/manual/current/en/index.html)ed

Usage
-----

```php
use \Goatherd\Uuid\Factory as Uuid;

// default: version 5 byte formated uuid
$uuid = Uuid::generate();

// version 4 string formated uuid
$uuid = Uuid::generate(Uuid::UUID_RANDOM, Uuid::FMT_STRING);

// get version 4 uuid from generator instance
$uuidGenerator = new \Goatherd\Uuid\V4();
$uuid = $uuidGenerator(Uuid::FTM_STRING);
```
