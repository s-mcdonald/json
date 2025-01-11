<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Fixtures\Entities\ClassesWithCreatesFromJson;

use SamMcDonald\Json\Serializer\Facets\CreatesFromJson;

class MyClassWithCreatesFromJson
{
    use CreatesFromJson;

    public string $name = "John Doe";
    public int $age = 30;
}
