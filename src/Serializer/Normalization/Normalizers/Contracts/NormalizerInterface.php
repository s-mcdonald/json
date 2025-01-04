<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalization\Normalizers\Contracts;

use stdClass;

interface NormalizerInterface
{
    public function normalize(mixed $input): stdClass;
}
