<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\NestingClasses;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

class NestableWithArray extends Nestable
{
    #[JsonProperty]
    public array $arrayVal = [];
}
