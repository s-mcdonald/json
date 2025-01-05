<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\JsonTypes;

use SamMcDonald\Json\Serializer\Attributes\JsonTypes\Contracts\JsonType;

class BooleanType extends JsonType
{
    public function __construct()
    {
        parent::__construct('boolean');
    }

    public function getPhpType(): string
    {
        return 'bool';
    }

    public function getCastType(): string
    {
        return 'bool';
    }
}
