<?php

declare(strict_types=1);

namespace SamMcDonald\Json;

use SamMcDonald\Json\Builder\JsonBuilder;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;
use SamMcDonald\Json\Serializer\Formatter\JsonFormatter;
use SamMcDonald\Json\Serializer\JsonSerializer;
use SamMcDonald\Json\Serializer\Transformer\JsonUtilities;

/**
 * @todo: need some refactoring so we can hold onto an instance of each service.
 */
final class Json
{
    private function __construct()
    {
    }

    public static function serialize(
        object $object,
        JsonFormat $format = JsonFormat::Compressed,
    ): string {
        return (new JsonSerializer())->serialize($object, $format);
    }

    public static function deserialize(string $json, string $classFqn): mixed
    {
        return (new JsonSerializer())->deserialize($json, $classFqn);
    }

    public static function createJsonBuilder(): JsonBuilder
    {
        return new JsonBuilder();
    }

    public static function prettify(string $json): string
    {
        return (new JsonFormatter())->pretty($json);
    }

    public static function uglify(string $json): string
    {
        return (new JsonFormatter())->ugly($json);
    }

    public static function isValid(string $json): bool
    {
        return (new JsonUtilities())->isValid($json);
    }

    public static function push(string $json, string $key, mixed $item): string|false
    {
        return (new JsonUtilities())->push($json, $key, $item);
    }

    /**
     * @todo: needs tests
     */
    public static function remove(string $json, string $property): string|false
    {
        return (new JsonUtilities())->remove($json, $property);
    }

    public static function toArray(string $json): array|false
    {
        return (new JsonUtilities())->toArray($json);
    }
}
