<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\AttributeReader;

use ReflectionAttribute;

class JsonPropertyReader
{
    /**
     * @param array<ReflectionAttribute> $attributes
     */
    public function getJsonPropertyName(string $existingNameOfMethodOrProperty, array $attributes): string
    {
        if (1 === count($attributes)) {
            return $this->getPropertyName($attributes[0], $existingNameOfMethodOrProperty);
        }

        return $existingNameOfMethodOrProperty;
    }

    private function getPropertyName(ReflectionAttribute $attribute, string $defaultName): string
    {
        $args = $attribute->getArguments();

        return $args[0] ?? $args['name'] ?? $defaultName;
    }
}
