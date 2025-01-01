<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding\Contracts;

interface EncodingResultInterface
{
    public function getBody(): mixed;

    public function getMessage(): string;

    public function isValid(): bool;
}
