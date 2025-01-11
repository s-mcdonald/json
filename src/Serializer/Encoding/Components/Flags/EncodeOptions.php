<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding\Components\Flags;

use InvalidArgumentException;

class EncodeOptions
{
    public function __construct(
        private AbstractFlags $flags,
        private int $depth = 512,
    ) {
        $this->setDepth($depth);
    }

    public function setDepth(int $depth): void
    {
        static::assertDepth($depth);

        $this->depth = $depth;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function setFlags(AbstractFlags $flags): void
    {
        $this->flags = $flags;
    }

    public function getFlags(): AbstractFlags
    {
        return $this->flags;
    }

    public function getFlagsValue(): int
    {
        return $this->flags->getFlags();
    }

    private static function assertDepth(int $depth): void
    {
        if ($depth <= 0) {
            throw new InvalidArgumentException('Depth must be greater than 0.');
        }
    }
}
