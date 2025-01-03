<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\NoAttributeClasses;

use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;

class SimpleScalaProperties implements JsonSerializable
{
    public string $name;
    public int $age;
    public bool $isActive;
}
