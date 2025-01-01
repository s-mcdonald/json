<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalizers\Context;

use ReflectionMethod;
use ReflectionProperty;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;
use stdClass;

readonly class Context
{
    public function __construct(
        private ReflectionProperty|ReflectionMethod $reflectionItem,
        private JsonSerializable $originalObject,
        private stdClass $classObject,
        private array $jsonPropertyAttributes,
        private string $propertyName,
    ) {
    }

    public function getJsonPropertyAttributes(): mixed
    {
        return $this->jsonPropertyAttributes;
    }

    public function getReflectionItem(): ReflectionMethod|ReflectionProperty
    {
        return $this->reflectionItem;
    }

    public function getOriginalObject(): JsonSerializable
    {
        return $this->originalObject;
    }

    public function getClassObject(): stdClass
    {
        return $this->classObject;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }
}
