<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;
use SamMcDonald\Json\Serializer\Hydration\Exceptions\HydrationException;
use SamMcDonald\Json\Serializer\Hydration\HydrationConfiguration;
use SamMcDonald\Json\Serializer\Hydration\HydrationTypeMap;

final class Hydrator
{
    private JsonPropertyReader $reader;

    public function __construct(
        private HydrationConfiguration|null $config = null,
    ) {
        $this->reader = new JsonPropertyReader();

        if (null === $this->config) {
            $this->config = new HydrationConfiguration();
        }
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
            if (false === is_string($propName)) {
                throw HydrationException::createHydrationParseWithBadPropertyNameException();
            }
            $reflectionProperty = $this->getPropertyFromReflection($reflectionClass, $propName);
            if (null === $reflectionProperty) {
                continue;
            }

            $assignType = HydrationTypeMap::get($reflectionProperty->getType()?->getName());
            if ($assignType !== gettype($value)) {
                if (false === $this->config->propertyHydrationTypeStrictMode) {
                    // can we cast between types
                }
                throw HydrationException::createHydrationParseTypeException(gettype($value), $assignType);
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
