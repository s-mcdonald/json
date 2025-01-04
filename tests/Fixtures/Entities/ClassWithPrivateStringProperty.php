<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Fixtures\Entities;

use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;

class ClassWithPrivateStringProperty implements JsonSerializable
{
    public function __construct(
        #[JsonProperty]
        private string $name
    ) {
    }
}
