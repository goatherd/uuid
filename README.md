Pure PHP UUID generator
=======================

Dual-licensed under BSDL (2-clause) or Apache 2.0 license 

Original author: [Fredrik Lindberg](https://github.com/fredriklindberg)

Adjustments
-----------

Optimised for use with PHP 5.3 or newer.
Coding conventions now are closer to PEAR standards.

The original single class was refactored as factory pattern.

Composer support was added and registered with [packagist](https://packagist.org/).
The component will be integrated with [goatherd library](https://github.com/goatherd/Goatherd-library).

With beta and stable release some improvements and unit tests will be added.

Please note that the interface is not yet final.

Usage
-----

```php
use \Goatherd\Uuid\Factory;
use \Goatherd\Uuid\UuidInterface;

// default version 5 byte formated uuid
$uuid = Factory::generate();

// version 4 string formated uuid
$uuid = Factory::generate(Factory::UUID_RANDOM, UuidInterface::FMT_STRING);

// get version 4 uuid from generator instance
$uuidGenerator = new \Goatherd\Uuid\V4();
$uuid = $uuidGenerator(Uuid::FMT_STRING);
```

Supported formats are `FMT_STRING`, `FMT_BYTE` and `FMT_BINARY`.
UUID version 1, 3, 4 and 5 are available.
