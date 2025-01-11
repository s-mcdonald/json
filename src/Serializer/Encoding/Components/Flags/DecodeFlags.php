<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding\Components\Flags;

readonly class DecodeFlags extends AbstractFlags
{
    public function withBigIntAsString(bool $value): self
    {
        return new self($this->getWithFlag(JSON_BIGINT_AS_STRING, $value));
    }

    public function hasBigIntAsString(): bool
    {
        return $this->hasFlag(JSON_BIGINT_AS_STRING);
    }

    public function withIgnoreInvalidUtf8(bool $value): self
    {
        return new self($this->getWithFlag(JSON_INVALID_UTF8_IGNORE, $value));
    }

    public function hasIgnoreInvalidUtf8(): bool
    {
        return $this->hasFlag(JSON_INVALID_UTF8_IGNORE);
    }
}
