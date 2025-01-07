<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Schema\Enums;

enum JsonValueType : string
{
    case String = 'string';
    case Integer = 'integer';
    case Double = 'double';
    case Boolean = 'boolean';
    case Array = 'array';
    case Object = 'object';
    case Null = 'null';

    public function isScalar(): bool
    {
        return false === (self::Array === $this || self::Object === $this);
    }
}
