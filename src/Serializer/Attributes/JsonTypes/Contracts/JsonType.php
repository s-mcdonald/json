<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Attributes\JsonTypes\Contracts;

abstract class JsonType
{
    public function __construct(
        private readonly string $type,
    ) {
    }

    final public function getJsonTypeString(): string
    {
        return $this->type;
    }

    abstract public function getPhpType(): string;

    public function getCastType(): string
    {
        return 'string';
    }
}
