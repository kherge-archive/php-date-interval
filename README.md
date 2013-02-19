DateInterval
============

[![Build Status](https://travis-ci.org/herrera-io/php-date-interval.png)](http://travis-ci.org/herrera-io/php-date-interval)

Provides additional functionality to the DateInterval class.

Summary
-------

The `DateInterval` class builds on the existing `DateInterval` class provided by PHP. With the new class, you may

- convert `DateInterval` to the [interval spec](http://php.net/manual/en/dateinterval.construct.php)
- convert `DateInterval` to the number of seconds
    - convert back to `DateInterval` from the number of seconds

> The conversion to seconds requires [a bit of explaining](https://github.com/herrera-io/php-date-interval/wiki/API#wiki-toSeconds).

Installation
------------

Add it to your list of Composer dependencies:

```sh
$ composer require herrera-io/date-interval=1.*
```

Usage
-----

```php
<?php

use Herrera\DateInterval\DateInterval;

$interval = new DateInteval('P2H');

echo $interval->toSeconds(); // "7200"
echo DateInterval::toSeconds($interval); // "7200"

echo $interval->toSpec(); // "P2H"
echo DateInterval::toSpec($interval); // "P2H"
```