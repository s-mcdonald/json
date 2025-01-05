<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\JsonTypes;

use SamMcDonald\Json\Serializer\Attributes\JsonTypes\Contracts\JsonType;

class ArrayType extends JsonType
{
    public function __construct()
    {
        parent::__construct('array');
    }

    public function getPhpType(): string
    {
        return 'array';
    }

    public function getCastType(): string
    {
        return 'array';
    }
}
