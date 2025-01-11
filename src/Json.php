<?php

declare(strict_types=1);

namespace SamMcDonald\Json;

use ArrayIterator;
use SamMcDonald\Json\Builder\JsonBuilder;
use SamMcDonald\Json\Serializer\Encoding\Components\JsonToArrayDecoder;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use SamMcDonald\Json\Serializer\Formatter\JsonFormatter;
use SamMcDonald\Json\Serializer\JsonSerializer;
use SamMcDonald\Json\Serializer\Transformer\JsonUtilities;

final class Json
{
    private static JsonUtilities|null $jsonUtilities = null;

    private static JsonFormatter|null $jsonFormatter = null;

    private static JsonSerializer|null $jsonSerializer = null;

    private function __construct(private string $json)
    {
    }

    private static function getJsonSerializer(): JsonSerializer
    {
        if (null === self::$jsonSerializer) {
            self::$jsonSerializer = new JsonSerializer();
        }

        return self::$jsonSerializer;
    }

    private static function getJsonUtilities(): JsonUtilities
    {
        if (null === self::$jsonUtilities) {
            self::$jsonUtilities = new JsonUtilities();
        }

        return self::$jsonUtilities;
    }

    private static function getJsonFormatter(): JsonFormatter
    {
        if (null === self::$jsonFormatter) {
            self::$jsonFormatter = new JsonFormatter();
        }

        return self::$jsonFormatter;
    }

    public function toPretty(): string
    {
        return self::prettify($this->json);
    }

    public function addProperty(string $key, mixed $value): self
    {
        $this->json = self::push($this->json, $key, $value);

        return $this;
    }

    public static function createFromString(string $json): self
    {
        return new self($json);
    }

    public static function serialize(
        object $object,
        JsonFormat $format = JsonFormat::Compressed,
    ): string {
        return self::getJsonSerializer()->serialize($object, $format);
    }

    public static function deserialize(string $json, string $classFqn): mixed
    {
        return self::getJsonSerializer()->deserialize($json, $classFqn);
    }

    public static function createJsonBuilder(): JsonBuilder
    {
        return new JsonBuilder();
    }

    public static function prettify(string $json): string
    {
        return self::getJsonFormatter()->pretty($json);
    }

    public static function uglify(string $json): string
    {
        return self::getJsonFormatter()->ugly($json);
    }

    public static function isValid(string $json): bool
    {
        return self::getJsonUtilities()->isValid($json);
    }

    public static function push(string $json, string $key, mixed $item): string|false
    {
        return self::getJsonUtilities()->push($json, $key, $item);
    }

    public static function remove(string $json, string $property): string|false
    {
        return self::getJsonUtilities()->remove($json, $property);
    }

    public static function toArray(string $json): array|false
    {
        return self::getJsonUtilities()->toArray($json);
    }

    public static function iterate(string $json): ArrayIterator
    {
        $decoded = (new JsonToArrayDecoder())->decode($json);

        if (false === $decoded->isValid()) {
            throw JsonSerializableException::unableToDecode();
        }

        return new ArrayIterator($decoded->getBody());
    }
}
