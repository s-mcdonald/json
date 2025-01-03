<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Json;
use SamMcDonald\Json\Tests\Fixtures\MyBackedEnum;
use SamMcDonald\Json\Tests\Fixtures\MyEnum;

class JsonTest extends TestCase
{
    public function testSimpleSerialize(): void
    {
        $jsonUgly = '{"name":"bar"}';

        $jsonPretty = <<<JSON
{
    "name": "bar"
}
JSON
        ;

        static::assertEquals(
            $jsonPretty,
            Json::prettify($jsonUgly),
        );
    }

    public function testBackedEnumTest(): void
    {
        $foo = MyBackedEnum::Foo;

        static::assertEquals(
            '{"MyBackedEnum":"foo"}',
            Json::serialize($foo),
        );
    }

    public function testEnumTest(): void
    {
        $foo = MyEnum::foo;

        static::assertEquals(
            '{"MyEnum":"foo"}',
            Json::serialize($foo),
        );
    }
}
