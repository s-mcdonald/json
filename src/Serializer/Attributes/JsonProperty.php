<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::TARGET_CLASS)]
class JsonProperty
{
    public function __construct(
        private string|null $name = null
    ) {
    }

    public function isNameValid(): bool
    {
        return self::getHasValidName($this->name);
    }

    public function getName(): string|null
    {
        return $this->name;
    }

    public static function getHasValidName(string|null $propertyName): bool
    {
        if (null === $propertyName) {
            return true;
        }

        if (str_contains($propertyName, ' ')) {
            return false;
        }

        if ('' === $propertyName) {
            return false;
        }

        if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9_]*$/', $propertyName)) {
            return false;
        }

        return true;
    }
}
