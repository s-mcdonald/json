<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\AttributeReader;

use ReflectionAttribute;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

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

    /**
     * @param array<ReflectionAttribute> $attributes
     */
    public function hasJsonPropertyAttributes(array $attributes): bool
    {
        if (empty($attributes)) {
            return false;
        }

        foreach ($attributes as $attribute) {
            if ($attribute instanceof JsonProperty) {
                return true;
            }
        }

        return false;
    }

    private function getPropertyName(ReflectionAttribute $attribute, string $defaultName): string
    {
        $args = $attribute->getArguments();

        return $args[0] ?? $args['name'] ?? $defaultName;
    }
}
