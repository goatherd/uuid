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

Usage
-----

    <?php
    use \Goatherd\Uuid\Factory;
    use \Goatherd\Uuid\UuidInterface;
    
    // default version 5 byte formated uuid
    $uuid = Factory::generate();
    
    // version 4 string formated uuid
    $uuid = Factory::generate(Factory::UUID_RANDOM, UuidInterface::FMT_STRING);
    
    // directly get version 4 uuid as string
    $uuid = \Goatherd\Uuid\V4::generate(UuidInterface::FMT_STRING);