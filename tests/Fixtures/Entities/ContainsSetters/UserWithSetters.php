<?php

declare(strict_types=1);

namespace Fixtures\Entities\ContainsSetters;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

class UserWithSetters
{
    public function __construct(
        private string $notMatchingName,
        private int $notMatchingAge,
    ) {}

    public function getName(): string
    {
        return $this->notMatchingName;
    }

    public function getAge(): int
    {
        return $this->notMatchingAge;
    }

    #[JsonProperty('userName')]
    public function setName(string $name): string
    {
        return $this->notMatchingName = $name;
    }

    #[JsonProperty('age')]
    public function setAge(int $age): int
    {
        $this->notMatchingAge = $age;
    }
}
