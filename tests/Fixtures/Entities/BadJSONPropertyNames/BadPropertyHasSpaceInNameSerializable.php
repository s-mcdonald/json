<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Fixtures\Entities\BadJSONPropertyNames;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

class BadPropertyHasSpaceInNameSerializable
{
    #[JsonProperty('user Name')]
    public string $badProperty;
}
