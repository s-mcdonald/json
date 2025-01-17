<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding\Components\Flags;

readonly class AbstractFlags
{
    protected function __construct(protected int $flags = 0)
    {
    }

    final public static function create(): static
    {
        return new static(0);
    }

    final public function getFlags(): int
    {
        return $this->flags;
    }

    protected function getWithFlag(int $flag, bool $value): int
    {
        $flags = $this->flags;
        if ($value) {
            $flags |= $flag;
        } else {
            $flags &= ~$flag;
        }

        return $flags;
    }

    protected function hasFlag(int $flag): bool
    {
        return (bool) ($this->flags & $flag);
    }
}
