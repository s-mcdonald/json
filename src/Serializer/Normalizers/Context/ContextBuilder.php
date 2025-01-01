<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalizers\Context;

use ReflectionMethod;
use ReflectionProperty;
use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

readonly class ContextBuilder
{
    public function __construct(
        private JsonPropertyReader $propertyReader,
    ) {
    }

    public function build(
        ReflectionProperty|ReflectionMethod $prop,
        $originalObject,
        $classObject,
    ): Context {
        $jsonPropertyAttributes = $prop->getAttributes(JsonProperty::class);
        $propertyName = $this->propertyReader->getJsonPropertyName($prop->getName(), $jsonPropertyAttributes);

        return new Context(
            $prop,
            $originalObject,
            $classObject,
            $jsonPropertyAttributes,
            $propertyName,
        );
    }
}
