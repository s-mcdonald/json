<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Fixtures\Entities;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;

class SimplePropertyClass implements JsonSerializable
{
    public function __construct(
        #[JsonProperty('userName')]
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
