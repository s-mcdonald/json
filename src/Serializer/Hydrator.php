<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

final class Hydrator
{
    public function __construct()
    {
    }

    /**
     * @throws ReflectionException
     */
    public function hydrate(object|array $data, string $fqClassName): object
    {
        if (false === class_exists($fqClassName)) {
            throw new InvalidArgumentException("The class '$fqClassName' does not exist.");
        }

        $reflectionClass = new ReflectionClass($fqClassName);
        $instance = $reflectionClass->newInstanceWithoutConstructor();

        foreach ($data as $key => $value) {
            if ($reflectionClass->hasProperty($key)) {
                $property = $reflectionClass->getProperty($key);
                $property->setValue($instance, $value);
            }
        }

        return $instance;
    }
}
