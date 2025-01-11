<?php

declare(strict_types=1);

if (false === function_exists('json_validate')) {
    function json_validate(string $json, int $depth = 512, int $flags = 0): bool
    {
        try {
            return null !== json_decode($json, false, $depth, JSON_THROW_ON_ERROR | $flags);
        } catch (Throwable $e) {
            return false;
        }
    }
}
