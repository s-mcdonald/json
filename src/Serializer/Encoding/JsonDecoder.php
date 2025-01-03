<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding;

use stdClass;

readonly class JsonDecoder
{
    public function __construct(
        private int $depth = 512,
    ) {
    }

    // @todo: WIP
    public function decode(string $jsonValue, string $fqClassName): stdClass
    {
        return json_decode($jsonValue, false, $this->depth, JSON_FORCE_OBJECT);
    }
}
