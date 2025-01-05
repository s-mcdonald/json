<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\JsonTypes;

use SamMcDonald\Json\Serializer\Attributes\JsonTypes\Contracts\JsonType;

class DoubleType extends JsonType
{
    public function __construct()
    {
        parent::__construct('double');
    }

    public function getPhpType(): string
    {
        return 'float';
    }

    public function getCastType(): string
    {
        return 'float';
    }
}
