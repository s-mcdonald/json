<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Fixtures\Enums;

enum MyBackedEnum:string
{
    case Foo = 'foo';
    case Bar = 'bar';
}
