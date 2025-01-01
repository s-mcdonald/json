<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Fixtures;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;

class ParentClassSerializable implements JsonSerializable
{
    #[JsonProperty('userName', deserialize: true)]
    public string $name;

    #[JsonProperty]
    public array $phoneNumbers;

    // serialization of value comes from the method
    // below. For deserialization, this value
    // will not be mapped back.
    private int $creditCard;

    #[JsonProperty('userAddress', deserialize: true)]
    private string $address;

    #[JsonProperty('creditCard')]
    public function getCreditCard(): int
    {
        return $this->creditCard;
    }
}
