Geo I/O WKT Generator
=====================

[![Build Status](https://github.com/geo-io/wkt-generator/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/geo-io/wkt-generator/actions/workflows/ci.yml)
[![Coverage Status](https://coveralls.io/repos/github/geo-io/wkt-generator/badge.svg?branch=main)](https://coveralls.io/github/geo-io/wkt-generator?branch=main)

Generates [Well-known text (WKT)](http://en.wikipedia.org/wiki/Well-known_text)
representations from geometric objects.

```php
class MyExtractor implements GeoIO\Extractor
{
    public function extractType($geometry)
    {
        if ($geometry instanceof MyPoint) {
            return self::TYPE_POINT;
        }

        // ...
    }

    public function extractCoordinatesFromPoint($point)
    {
        return array(
            'x' => $point->getX(),
            'y' => $point->getY(),
            'z' => null,
            'm' => null,
        );
    }

    // ...
}

$extractor = MyExtractor();
$generator = new GeoIO\WKT\Generator\Generator($extractor);

echo $generator->generate(new MyPoint(1, 2));
// Outputs:
// POINT(1 2)
```

Installation
------------

Install [through composer](http://getcomposer.org). Check the
[packagist page](https://packagist.org/packages/geo-io/wkt-generator) for all
available versions.

```bash
composer require geo-io/wkt-generator
```

License
-------

Copyright (c) 2014-2022 Jan Sorgalla. Released under the [MIT License](LICENSE).
