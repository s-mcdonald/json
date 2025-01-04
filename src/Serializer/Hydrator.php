<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;

final class Hydrator
{
    private JsonPropertyReader $reader;

    public function __construct()
    {
        $this->reader = new JsonPropertyReader();
    }

    /**
     * @throws ReflectionException
     */
    public function hydrate(object|array $object, string $fqClassName): object
    {
        if (false === class_exists($fqClassName)) {
            throw new InvalidArgumentException("The class '$fqClassName' does not exist.");
        }

        $reflectionClass = new ReflectionClass($fqClassName);
        $instance = $reflectionClass->newInstanceWithoutConstructor();

        foreach ($object as $propName => $value) {
            $reflectionProperty = $this->getPropertyFromReflection($reflectionClass, $propName);
            if (null === $reflectionProperty) {
                continue;
            }

            $reflectionProperty->setValue($instance, $value);
        }

        return $instance;
    }

    private function getPropertyFromReflection(ReflectionClass $reflectionClass, string $propName): ReflectionProperty|null
    {
        if ($property = $this->reader->findPropertyByAttributeWithArgument($reflectionClass, 'name', $propName)) {
            return $property;
        }

        if ($reflectionClass->hasProperty($propName)) {
            return $reflectionClass->getProperty($propName);
        }

        return null;
    }
}
