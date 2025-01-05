<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Exceptions;

use RuntimeException;

class JsonSerializableException extends RuntimeException
{
    public static function hasTooManyJsonProperties(): self
    {
        return new self('Cannot serialize object with more than 1 JsonProperty');
    }

    public static function unableToDecode(): self
    {
        return new self('Unable to decode json');
    }
}
