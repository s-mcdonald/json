<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\JsonTypes;

use SamMcDonald\Json\Serializer\Attributes\JsonTypes\Contracts\JsonType;

class NullType extends JsonType
{
    public function __construct()
    {
        parent::__construct('null');
    }

    public function getPhpType(): string
    {
        return 'NULL';
    }

    public function getCastType(): string
    {
        return 'NULL';
    }
}
