<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Schema\Rules;

class StringLengthRule extends AbstractRule
{
    private function __construct(
        private int|null $min = null,
        private int|null $max = null,
    ){
    }

    public static function create(): self
    {
        return new self(null, null);
    }

    public function check(mixed $value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException("Value must be a string.");
        }

        $length = mb_strlen($value, 'UTF-8');

        if ($this->min !== null && $length < $this->min) {
            throw new \LengthException("String is shorter than the minimum allowed length of {$this->min}.");
        }

        if ($this->max !== null && $length > $this->max) {
            throw new \LengthException("String is longer than the maximum allowed length of {$this->max}.");
        }
    }

    public static function minLength(int $min): self
    {
        return new self($min, null);
    }

    public static function maxLength(int $max): self
    {
        return new self(null, $max);
    }

    public static function minMax(int $min, int $max): self
    {
        return new self($min, $max);
    }
}
