<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalization\Normalizers;

use SamMcDonald\Json\Builder\JsonBuilder;
use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;

final readonly class JSConfigObjectNormalizer
{
    public function __construct(
        private JsonPropertyReader $propertyReader,
    ) {
    }

    public function normalize(JsonSerializable $propertyValue): JsonBuilder
    {
        return new JsonBuilder();
    }
}
