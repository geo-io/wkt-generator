Geo I/O WKT Generator
=====================

[![Build Status](https://travis-ci.org/geo-io/wkt-generator.svg?branch=master)](https://travis-ci.org/geo-io/wkt-generator)
[![Coverage Status](https://img.shields.io/coveralls/geo-io/wkt-generator.svg?style=flat)](https://coveralls.io/r/geo-io/wkt-generator)

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
$generator = new GeoIO\WKT\Generator($extractor);

echo $generator->generate(new MyPoint(1, 2));
// Outputs:
// POINT(1 2)
```

Installation
------------

Install [through composer](http://getcomposer.org). Check the
[packagist page](https://packagist.org/packages/geo-io/wkt-generator) for all
available versions.

```json
{
    "require": {
        "geo-io/wkt-generator": "0.1.*@dev"
    }
}
```

License
-------

Geo I/O WKT Generator is released under the [MIT License](LICENSE).
