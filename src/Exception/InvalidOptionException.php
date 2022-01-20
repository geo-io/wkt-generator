<?php

declare(strict_types=1);

namespace GeoIO\WKT\Generator\Exception;

use InvalidArgumentException;

final class InvalidOptionException extends InvalidArgumentException implements Exception
{
    public static function create(
        string $name,
        mixed $value,
        array $expected,
    ): self {
        return new self(
            sprintf(
                'Invalid value for option %s passed: %s (Expected %s)',
                $name,
                json_encode($value, JSON_THROW_ON_ERROR),
                implode(', ', array_map('json_encode', $expected)),
            ),
        );
    }
}
