<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Facets;

use SamMcDonald\Json\Serializer\JsonSerializer;

trait CreatesFromJson
{
    public static function createFromJson(string $json): static
    {
        return (new JsonSerializer())->deserialize($json, static::class);
    }
}
