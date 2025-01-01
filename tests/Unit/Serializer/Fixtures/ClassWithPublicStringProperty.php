<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Fixtures;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;

class ClassWithPublicStringProperty implements JsonSerializable
{
    #[JsonProperty]
    public string $name;
}
