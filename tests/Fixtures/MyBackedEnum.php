<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Fixtures;

use SamMcDonald\Json\Serializer\Contracts\JsonEnum;

enum MyBackedEnum:string implements JsonEnum
{
    case Foo = 'foo';
    case Bar = 'bar';
}
