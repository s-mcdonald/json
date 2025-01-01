<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer;

class JsonSerializer
{
    private const EMPTY = '';

    public function serialize(Contracts\JsonSerializable $object, Enums\JsonFormat $format): string
    {
        return self::EMPTY;
    }
}
