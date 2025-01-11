<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding\Components\Flags;

readonly class EncodeFlags extends AbstractFlags
{
    public function withUnescapeSlashes(bool $value): self
    {
        return new self($this->getWithFlag(JSON_UNESCAPED_SLASHES, $value));
    }

    public function hasUnescapeSlashes(): bool
    {
        return $this->hasFlag(JSON_UNESCAPED_SLASHES);
    }

    public function withUnescapeUnicode(bool $value): self
    {
        return new self($this->getWithFlag(JSON_UNESCAPED_UNICODE, $value));
    }

    public function hasUnescapeUnicode(): bool
    {
        return $this->hasFlag(JSON_UNESCAPED_UNICODE);
    }

    public function withHexQuoteTags(bool $value): self
    {
        return new self($this->getWithFlag(JSON_HEX_QUOT, $value));
    }

    public function hasHexQuoteTags(): bool
    {
        return $this->hasFlag(JSON_HEX_QUOT);
    }

    public function withHexAposTags(bool $value): self
    {
        return new self($this->getWithFlag(JSON_HEX_APOS, $value));
    }

    public function hasHexAposTags(): bool
    {
        return $this->hasFlag(JSON_HEX_APOS);
    }

    public function withHexAmpTags(bool $value): self
    {
        return new self($this->getWithFlag(JSON_HEX_AMP, $value));
    }

    public function hasHexAmpTags(): bool
    {
        return $this->hasFlag(JSON_HEX_AMP);
    }

    public function withHexTags(bool $value): self
    {
        return new self($this->getWithFlag(JSON_HEX_TAG, $value));
    }

    public function hasHexTags(): bool
    {
        return $this->hasFlag(JSON_HEX_TAG);
    }
}
