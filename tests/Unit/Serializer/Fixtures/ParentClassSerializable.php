<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Fixtures;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

class ParentClassSerializable
{
    #[JsonProperty('userName')]
    public string $name;

    #[JsonProperty]
    public array $phoneNumbers;

    #[JsonProperty('child')]
    public GoodChildObjectSerializable $child;

    public function __construct(
        private readonly int|null $creditCard,
        #[JsonProperty('userAddress')] private string $address,
    ) {
    }

    #[JsonProperty('creditCard')]
    public function getCreditCard(): int|null
    {
        return $this->creditCard;
    }
}
