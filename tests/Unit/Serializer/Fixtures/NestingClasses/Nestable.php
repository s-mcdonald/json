<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\NestingClasses;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

class Nestable
{
    #[JsonProperty]
    public int $intVal = 123;

    #[JsonProperty]
    public string $stringVal = "foo";

    #[JsonProperty]
    public Nestable|null $objVal = null;
}
