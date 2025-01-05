<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class JsonProperty
{
    public function __construct(
        private string|null $name = null,
        private bool $deserialize = false,
    ) {
    }

    public static function getHasValidName(string $propertyName): bool
    {
        if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9-_]*$/', $propertyName)) {
            return false;
        }

        return true;
    }

    public function getName(): string|null
    {
        return $this->name;
    }

    public function getDeserialize(): bool
    {
        return $this->deserialize;
    }
}
