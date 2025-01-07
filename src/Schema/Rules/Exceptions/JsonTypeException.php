<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Schema\Rules\Exceptions;

use RuntimeException;

class JsonTypeException extends RuntimeException
{
    public function __construct(string $message) {
        parent::__construct($message);
    }
}
