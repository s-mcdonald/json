<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\JsonTypes;

use SamMcDonald\Json\Serializer\Attributes\JsonTypes\Contracts\JsonType;

class StringType extends JsonType
{
    public function __construct()
    {
        parent::__construct('string');
    }

    public function getPhpType(): string
    {
        return 'string';
    }
}
