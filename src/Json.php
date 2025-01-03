<?php

declare(strict_types=1);

namespace SamMcDonald\Json;

use SamMcDonald\Json\Builder\JsonBuilder;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;
use SamMcDonald\Json\Serializer\Encoding\JsonDecoder;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;
use SamMcDonald\Json\Serializer\JsonSerializer;
use stdClass;

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

    // @todo: WIP
    public static function deserialize(string $json, string $classFqn): stdClass
    {
        // @todo: create the decoder - for now use basic decoder
        return (new JsonDecoder())->decode($json, $classFqn);
        // Step 1 - Ensure $classFqn is valid type
        // Step 2 - create a map
        // Step 3 - decode to stdClass
        // Step 4 - invoke/ move data to new entity.
    }

    public static function createJsonBuilder(): JsonBuilder
    {
        return new JsonBuilder();
    }
}
