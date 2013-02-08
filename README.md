DateInterval
============

[![Build Status](https://travis-ci.org/herrera-io/php-date-interval.png)](http://travis-ci.org/herrera-io/php-date-interval)

Provides additional functionality to the DateInterval class.

Summary
-------

The library's `DateInterval` class adds new functionality:

- convert `DateInterval` to [interval spec](http://php.net/manual/en/dateinterval.construct.php)
- convert `DateInterval` to seconds

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
