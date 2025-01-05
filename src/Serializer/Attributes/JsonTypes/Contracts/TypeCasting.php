<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\JsonTypes\Contracts;

interface TypeCasting
{
    public function casts($value): mixed;
}
