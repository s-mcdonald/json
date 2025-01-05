<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\JsonTypes;

use SamMcDonald\Json\Serializer\Attributes\JsonTypes\Contracts\JsonType;

class ObjectType extends JsonType
{
    public function __construct()
    {
        parent::__construct('object');
    }

    public function getPhpType(): string
    {
        return 'object';
    }

    public function getCastType(): string
    {
        return 'object';
    }
}
