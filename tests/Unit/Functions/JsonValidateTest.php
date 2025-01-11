<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Functions;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class JsonValidateTest extends TestCase
{
    #[DataProvider('provideInValidJson')]
    public function testValidateBadJson($inValidJson): void
    {
        static::assertFalse(json_validate($inValidJson));
    }

    #[DataProvider('provideValidJson')]
    public function testValidateValidJson($validJson): void
    {
        static::assertTrue(json_validate($validJson));
    }

    public static function provideInValidJson(): array
    {
        return [
            ['{'],
            ['{"foo"}'],
            ['{"foo":}'],
            ['{"foo": "bar', 'bar"'],
            ['{"foo": "bar", "baz":'],
            ['{"foo": "bar", "baz": 123, "qux": true, "quux": false, "corge": null, "grault": []'],
        ];
    }

    public static function provideValidJson(): array
    {
        return [
            ['{}'],
            ['{"foo": "bar"}'],
            ['{"foo": "bar", "baz": 123}'],
            ['{"foo": "bar", "baz": 123, "qux": true}'],
            ['{"foo": "bar", "baz": 123, "qux": true, "quux": false}'],
            ['{"foo": "bar", "baz": 123, "qux": true, "quux": false, "corge": null}'],
            ['{"foo": "bar", "baz": 123, "qux": true, "quux": false, "corge": null, "grault": []}'],
        ];
    }
}
