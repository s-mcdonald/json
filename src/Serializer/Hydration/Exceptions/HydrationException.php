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
}
