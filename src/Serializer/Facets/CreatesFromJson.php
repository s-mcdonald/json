<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Facets;

use SamMcDonald\Json\Serializer\JsonSerializer;

trait CreatesFromJson
{
    public function createFromJson(string $json): string
    {
        return (new JsonSerializer())->deserialize($json, static::class);
    }
}
