<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\AttributeReader;

use InvalidArgumentException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;

/**
 * This class needs a lot more work. Its working for now in its current purpose but definitely not
 * multipurpose. The reader needs to accept a source, and the public methods to
 * iterate and return values.
 *
 * Its current implementation is more like a helper, but we should not be
 * writing code like this!
 */
class JsonPropertyReader
{
    /**
     * @param array<ReflectionAttribute> $attributes
     */
    public function getJsonPropertyName(string $defaultPropertyName, array $attributes): string
    {
        if (1 === count($attributes)) {
            return $this->getPropertyName($attributes[0], $defaultPropertyName);
        }

        return $defaultPropertyName;
    }

    /**
     * @param array<ReflectionAttribute> $attributes
     */
    public function hasJsonPropertyAttributes(array $attributes): bool
    {
        if (empty($attributes)) {
            return false;
        }

        foreach ($attributes as $attribute) {
            assert($attribute instanceof ReflectionAttribute);
            if (JsonProperty::class === $attribute->getName()) {
                return true;
            }
        }

        return false;
    }

    public function findPropertyByAttributeWithArgument(ReflectionClass $reflectionClass, string $argumentName, string $propName): ReflectionProperty|null
    {
        foreach ($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes(JsonProperty::class);
            foreach ($attributes as $attribute) {
                $arguments = $attribute->getArguments();
                if (
                    isset($arguments[$argumentName]) && $arguments[$argumentName] === $propName
                    || isset($arguments[0]) && $arguments[0] === $propName
                ) {
                    return $property;
                }
            }
        }

        return null;
    }

    private function getPropertyName(ReflectionAttribute $attribute, string $defaultName): string
    {
        $args = $attribute->getArguments();

        if (0 === count($args)) {
            return $defaultName;
        }

        $newName = $args[0] ?? $args['name'] ?? null;

        if (null === $newName || '' === $newName || str_contains($newName, ' ')) {
            throw new InvalidArgumentException('Invalid property name');
            //            throw new JsonSerializableException('Invalid JsonProperty name.');
        }

        return $newName;
    }
}
