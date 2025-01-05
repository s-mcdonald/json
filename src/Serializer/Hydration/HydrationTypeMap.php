<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Hydration;

class HydrationTypeMap
{
    public const TYPE_MAP = [
        'int' => 'integer',
        'float' => 'double',
        'bool' => 'boolean',
        'string' => 'string',
        'array' => 'array',
        'object' => 'object',
        'null' => 'NULL',
        'resource' => 'resource',
        'mixed' => 'unknown type',
    ];

    public static function get(string|null $type): string
    {
        return self::TYPE_MAP[$type] ?? 'mixed';
    }
}
