<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Fixtures\Entities\BadClass;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

class VeryBadClass
{
    public function __construct() {
        throw new \RuntimeException();
    }

    #[JsonProperty(name: 'foo')]
    public function setFoo(string $foo): void
    {
        throw new \RuntimeException();
    }
}
