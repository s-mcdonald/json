<?php

declare(strict_types=1);

namespace SamMcDonald\Json;

use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;
use SamMcDonald\Json\Serializer\JsonSerializer;

final class Json
{
    private function __construct()
    {
    }

    public static function serialize(
        JsonSerializable $object,
        JsonFormat $format = JsonFormat::Compressed,
    ): string {
        return (new JsonSerializer())->serialize($object, $format);
    }
}
