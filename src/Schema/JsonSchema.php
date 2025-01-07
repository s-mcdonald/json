<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Schema;

class JsonSchema
{
    private bool $allowUnDefinedProperties;

    private array $properties = [];

    public function __construct(bool $allowUnDefinedProperties, Property ...$properties)
    {
        $this->allowUnDefinedProperties = $allowUnDefinedProperties;
    }

    public function assertProperty(string $prop, mixed $value): void
    {
        foreach ($this->properties as $property) {
            assert($property instanceof Property);
            if ($property->getName() === $prop) {
                $property->assertValue($value);
            }
        }
    }
}
