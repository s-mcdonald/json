<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Fixtures\Entities;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

class SimplePropertiesNoOverrideClass
{
    public function __construct(
        #[JsonProperty]
        private string $name,
        #[JsonProperty]
        private int $age,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): int
    {
        return $this->age;
    }
}
