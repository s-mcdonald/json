<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalization\Normalizers\Context;

use ReflectionMethod;
use ReflectionProperty;
use SamMcDonald\Json\Builder\JsonBuilder;

readonly class Context
{
    public function __construct(
        private ReflectionProperty|ReflectionMethod $reflectionItem,
        private object $originalObject,
        private JsonBuilder $classObject,
        private array $jsonPropertyAttributes,
        private string $propertyName,
    ) {
    }

    public function getJsonPropertyAttributes(): array
    {
        return $this->jsonPropertyAttributes;
    }

    public function getReflectionItem(): ReflectionMethod|ReflectionProperty
    {
        return $this->reflectionItem;
    }

    public function getOriginalObject(): object
    {
        return $this->originalObject;
    }

    public function getClassObject(): JsonBuilder
    {
        return $this->classObject;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }
}
