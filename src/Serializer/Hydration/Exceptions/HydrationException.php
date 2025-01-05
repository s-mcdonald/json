<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Hydration\Exceptions;

use RuntimeException;

class HydrationException extends RuntimeException
{
    public static function createHydrationParseException(): self
    {
        return new self('Unable to parse hydration data.');
    }

    public static function createHydrationParseTypeException(string $jsonType, string $propertyType): self
    {
        return new self("Unable to parse hydration data: Expected {$jsonType} but got {$propertyType}");
    }

    public static function createHydrationParseWithBadPropertyNameException(): self
    {
        return new self('Unable to parse hydration data: Bad Property name');
    }

    public static function createMethodHasTooManyJsonProperties(string $methodName): self
    {
        return new self('Method ' . $methodName . ' has too many json properties.');
    }

    public static function createTooManyRequiredParameters(string $getName): self
    {
        return new self('Method ' . $getName . ' has too many required parameters.');
    }
}
