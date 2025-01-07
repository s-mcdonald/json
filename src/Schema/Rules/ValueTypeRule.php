<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Schema\Rules;

use SamMcDonald\Json\Schema\Enums\JsonValueType;
use SamMcDonald\Json\Schema\Rules\Exceptions\JsonTypeException;

class ValueTypeRule extends AbstractRule
{
    private array $typesAllowed;

    private function __construct(JsonValueType ...$typesAllowed)
    {
        $this->typesAllowed = [...$typesAllowed];
    }

    public function check(mixed $value): void
    {
        if (false === in_array(\gettype($value), $this->typesAllowed, true)) {
            throw new JsonTypeException('Invalid type');
        }
    }

    public static function create(): self
    {
        return new self(...JsonValueType::cases());
    }

    public static function requireAnyOf(JsonValueType ...$typesAllowed): self
    {
        return new self(...$typesAllowed);
    }

    public static function requireString(): self
    {
        return new self(JsonValueType::String);
    }

    public static function requireInteger(): self
    {
        return new self(JsonValueType::Integer);
    }

    public static function requireFloat(): self
    {
        return new self(JsonValueType::Double);
    }

    public static function requireBool(): self
    {
        return new self(JsonValueType::Boolean);
    }

    public static function requireObject(): self
    {
        return new self(JsonValueType::Object);
    }
}
