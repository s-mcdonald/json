<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Schema;

use ValueError;

class PropertyName
{
    public function __construct(private string $name)
    {
        if (str_contains($this->name, ' ')) {
            throw new ValueError("cant have space in name");
        }

        if (trim($this->name) === '') {
            throw new ValueError("A JSON property name cannot be empty.");
        }

        if (preg_match('/[\x00-\x1F\x22\x5C]/u', $this->name)) {
            throw new ValueError("A JSON property name contains invalid characters.");
        }

        if (!mb_check_encoding($this->name, 'UTF-8')) {
            throw new ValueError("A JSON property name must be valid UTF-8.");
        }

        if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9-_]*$/', $this->name)) {
            throw new ValueError('Invalid property name');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }
}
