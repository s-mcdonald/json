<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding\Validator\Contracts;

interface JsonValidatorInterface
{
    public function validate(string $json): bool;

    public function isValid(string $json): bool;

    public function getLastErrorMessage(): string|null;
}
