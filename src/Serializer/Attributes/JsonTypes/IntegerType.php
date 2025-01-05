<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\JsonTypes;

use SamMcDonald\Json\Serializer\Attributes\JsonTypes\Contracts\JsonType;

class IntegerType extends JsonType
{
    public function __construct()
    {
        parent::__construct('integer');
    }

    public function getPhpType(): string
    {
        return 'int';
    }

    public function getCastType(): string
    {
        return 'int';
    }
}
