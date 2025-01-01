<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding\Contracts;

interface EncoderInterface
{
    public function encode($value): EncodingResultInterface;
}
