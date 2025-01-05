<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Facets;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Facets\SerializesToJson;

#[CoversClass(SerializesToJson::class)]
class SerializesToJsonTest extends TestCase
{
    public function testSerializeToJson(): void
    {
        $myClass = new class {
            use SerializesToJson;

            #[JsonProperty]
            private int $integer = 123;
            private string $string = 'foo';
            private array $array = ['foo', 'bar'];
            private bool $boolean = true;
            private float $float = 1.23;
            private null $null = null;
            public object $myobject;

            public function toJson(): string
            {
                return $this->serializeToJson();
            }
        };

        $nested = (object) ["foo" => "bar"];
        $myClass->myobject = $nested;

        $expected = <<<JSON
{
    "integer": 123,
    "string": "foo",
    "array": [
        "foo",
        "bar"
    ],
    "boolean": true,
    "float": 1.23,
    "null": null,
    "myobject": {
        "foo": "bar"
    }
}
JSON;

        static::assertEquals($expected, $myClass->toJson());
    }

    public function testSerializeToJsonWithMapping(): void
    {
        $myClass = new class {
            use SerializesToJson;

            private int $integer = 123;
            private string $string = 'foo';
            private array $array = ['foo', 'bar'];
            private bool $boolean = true;
            private float $float = 1.23;
            private null $null = null;
            public object $myobject;

            public function toJson(): string
            {
                $map = [
                    "integer",
                    "string",
                    "array",
                    "boolean",
                    "myobject"
                ];

                return $this->serializeToJson($map);
            }
        };

        $nested = (object) ["foo" => "bar"];
        $myClass->myobject = $nested;

        $expected = <<<JSON
{
    "integer": 123,
    "string": "foo",
    "array": [
        "foo",
        "bar"
    ],
    "boolean": true,
    "myobject": {
        "foo": "bar"
    }
}
JSON;

        static::assertEquals($expected, $myClass->toJson());
    }
}
