<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Loaders;

interface LoaderInterface
{
    public function load(mixed $data): string;
}
