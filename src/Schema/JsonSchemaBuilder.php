<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Schema;

use SamMcDonald\Json\Schema\Rules\AbstractRule;

class JsonSchemaBuilder
{
    private bool $allowUnDefinedProperties = true;

    private array $properties = [];

    public function __construct()
    {
    }

    public function setAllowUnDefinedProperties(bool $allow): JsonSchemaBuilder
    {
        $this->allowUnDefinedProperties = $allow;
        return $this;
    }

    public function defineProperty(PropertyName $propertyName, AbstractRule $rule, bool $requiredProperty = false): JsonSchemaBuilder
    {
        $this->properties[] = new Property($propertyName, $rule, $requiredProperty);
        return $this;
    }

    public function build(): JsonSchema
    {
        return new JsonSchema($this->allowUnDefinedProperties, ...$this->properties);
    }
}
