<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Fixtures;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

class ClassWithPublicStringProperty
{
    #[JsonProperty]
    public string $name;
}
