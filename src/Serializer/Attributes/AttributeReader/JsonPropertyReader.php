<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\AttributeReader;

use ReflectionAttribute;
use ReflectionProperty;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

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

    /**
     * @return array<JsonProperty>
     */
    private function getJsonPropertyAttributes(ReflectionProperty $prop): array
    {
        return $prop->getAttributes(JsonProperty::class);
    }

    private function getPropertyName(ReflectionAttribute $attribute, string $defaultName): string
    {
        $args = $attribute->getArguments();

        return $args[0] ?? $args['name'] ?? $defaultName;
    }
}
