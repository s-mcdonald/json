<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Fixtures;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;

class BadPropertyNamesSerializable implements JsonSerializable
{
    #[JsonProperty('user Name', deserialize: true)]
    public string $name;
}
