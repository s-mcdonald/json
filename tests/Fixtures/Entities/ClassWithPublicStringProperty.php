<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Fixtures\Entities;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Attributes\JsonTypes\StringType;

class ClassWithPublicStringProperty
{
    #[JsonProperty]
    public string $name;
}
