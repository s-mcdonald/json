<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Fixtures;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;

class ClassWithMethodAndConstructor implements JsonSerializable
{
    #[JsonProperty('userName', deserialize: true)]
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
