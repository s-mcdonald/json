<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding\Contracts;

use SamMcDonald\Json\Serializer\Enums\JsonFormat;

interface ArrayEncoderInterface
{
    public function encode(array $value, JsonFormat $format = JsonFormat::Pretty): EncodingResultInterface;
}
