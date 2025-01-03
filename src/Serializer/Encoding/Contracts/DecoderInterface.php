<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding\Contracts;

interface DecoderInterface
{
    public function decode(string $jsonValue, string $fqClassName): DecodingResultInterface;
}
