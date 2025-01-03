<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Fixtures;

use SamMcDonald\Json\Serializer\Contracts\JsonEnum;

enum MyEnum implements JsonEnum
{
    case foo;
    case Bar;
}
