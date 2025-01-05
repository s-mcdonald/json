<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Fixtures\Entities;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

class ClassWithMethodAndConstructor
{
    #[JsonProperty('userName')]
    public string $name;

    #[JsonProperty]
    public array $phoneNumbers;

    public function __construct(
        private readonly int|null $creditCard = null,
    ) {
    }

    #[JsonProperty('creditCard')]
    public function getCreditCard(): int|null
    {
        return $this->creditCard;
    }
}
