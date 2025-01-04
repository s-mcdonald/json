<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalization\Normalizers;

use SamMcDonald\Json\Builder\JsonBuilder;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use stdClass;

/**
 * Normalize from array to stdClass.
 */
class ArrayNormalizer
{
    public function __construct()
    {
    }

    public function normalize(array $array): stdClass
    {
        return $this->transferToJsonBuilder($array)->toStdClass();
    }

    private function transferToJsonBuilder(array $array): JsonBuilder
    {
        $jsonBuilder = new JsonBuilder();

        foreach ($array as $property => $value) {
            $this->processProperty($property, $value, $jsonBuilder);
        }

        return $jsonBuilder;
    }

    private function processProperty(string $property, $value, JsonBuilder $jsonBuilder): void
    {
        if (false === $this->isSerializable($value)) {
            return;
        }

        $this->assignToStdClass($property, $value, $jsonBuilder);
    }

    private function assignToStdClass($propertyName, $propertyValue, JsonBuilder $classObject): void
    {
        match (\gettype($propertyValue)) {
            'array' => $classObject->addProperty($propertyName, $this->mapArrayContents($propertyValue)),
            'NULL', 'boolean', 'string', 'integer', 'double' => $classObject->addProperty($propertyName, $propertyValue),
            default => throw new JsonSerializableException('Invalid type.'),
        };
    }

    private function isSerializable($propertyValue): bool
    {
        if (
            null === $propertyValue || is_scalar($propertyValue) || is_array($propertyValue)
        ) {
            return true;
        }

        if ($propertyValue instanceof JsonSerializable) {
            return true;
        }

        return false;
    }

    private function mapArrayContents(array $array): array
    {
        $newArray = [];
        foreach ($array as $value) {
            $newArray[] = match (true) {
                is_null($value) => null,
                is_bool($value), is_scalar($value) => $value,
                is_array($value) => $this->mapArrayContents($value),
                default => throw new JsonSerializableException('Invalid type in array.'),
            };
        }

        return $newArray;
    }
}
