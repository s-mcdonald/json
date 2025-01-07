<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Schema\Rules;

class StringCaseRule extends AbstractRule
{
    private function __construct(
        private string|null $strCase
    ){
    }

    public static function create(): self
    {
        return new self(null);
    }

    public function check(mixed $value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException("The value must be a string to enforce uppercase check.");
        }

        if ($this->strCase === 'upper') {
            if ($value !== mb_strtoupper($value, 'UTF-8')) {
                throw new \InvalidArgumentException("The value must be entirely uppercase.");
            }
        }

        if ($this->strCase === 'lower') {
            if ($value !== mb_strtolower($value, 'UTF-8')) {
                throw new \InvalidArgumentException("The value must be entirely uppercase.");
            }
        }
    }

    public static function upperCase(): self
    {
        return new self('upper');
    }

    public static function lowerCase(): self
    {
        return new self('lower');
    }
}
