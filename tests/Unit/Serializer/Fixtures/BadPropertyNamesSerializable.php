<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Fixtures;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;


class BadPropertyNamesSerializable
{
    #[JsonProperty('user Name')]
    public string $name;
}
