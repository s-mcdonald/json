<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Hydration;

class HydrationTypeMap
{
    final public const VALID_TYPE_HINT_MAP = [
        'int' => 'integer',
        'float' => 'double',
        'bool' => 'boolean',
        'string' => 'string',
        'array' => 'array',
        'object' => 'object',
        'null' => 'NULL',
    ];

    final public const EXTENDED_TYPE_HINT_MAP = [
        'resource' => 'resource',
        'mixed' => 'mixed',
        'callable' => 'callable',
        'iterable' => 'iterable',
        'void' => 'void',
        'never' => 'never',
        'self' => 'self',
        'static' => 'static',
    ];

    public static function get(string|null $type): string
    {
        return self::VALID_TYPE_HINT_MAP[$type] ?? 'mixed';
    }
}
