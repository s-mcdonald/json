<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding;

use SamMcDonald\Json\Serializer\Encoding\Contracts\EncodingResultInterface;

class JsonEncodingResult implements EncodingResultInterface
{
    public function __construct(
        protected mixed $body,
        protected string $message = '',
        protected bool $isValid = false,
    ) {
    }

    final public function getBody(): mixed
    {
        return $this->body;
    }

    final public function getMessage(): string
    {
        return $this->message;
    }

    final public function isValid(): bool
    {
        return $this->isValid;
    }
}
