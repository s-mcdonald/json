<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Hydration\Exceptions;

class HydrationException extends \RuntimeException
{
    public static function createHydrationParseException(): self
    {
        return new self("Unable to parse hydration data.");
    }

    public static function createHydrationParseWithBadPropertyNameException(): self
    {
        return new self("Unable to parse hydration data: Bad Property name");
    }
}
