<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Fixtures\Entities;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

class BadPropertyNamesSerializable
{
    #[JsonProperty('user Name')]
    public string $name;
}
