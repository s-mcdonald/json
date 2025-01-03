<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Json;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\BadPropertyNamesSerializable;
use SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\ClassWithMethodAndConstructor;
use SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\ClassWithPublicStringProperty;
use SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\GoodChildObjectSerializable;
use SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\NestingClasses\Nestable;
use SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\NestingClasses\NestableWithArray;
use SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\ParentClassSerializable;

class SerializerTest extends TestCase
{
    public function testSimpleSerializeWithUninitializedCausesException(): void
    {
        $this->expectException(JsonSerializableException::class);
        $this->expectExceptionMessage('Value not initialized');

        $sut = new ClassWithPublicStringProperty();

        Json::serialize($sut);
    }

    public function testSimpleSerialize(): void
    {
        $sut = new ClassWithPublicStringProperty();
        $sut->name = 'bar';

        static::assertEquals(
            '{"name":"bar"}',
            Json::serialize($sut),
        );
    }

    public function testSerialize(): void
    {
        $sut = new ClassWithMethodAndConstructor(1234);
        $sut->name = 'foo';
        $sut->phoneNumbers = ['1234', '5678'];

        static::assertEquals(
            '{"userName":"foo","phoneNumbers":["1234","5678"],"creditCard":1234}',
            Json::serialize($sut),
        );
    }

    /**
     * When a bad name is presented default to the property name.
     */
    public function testSerializeWithBadPropertyNames(): void
    {
        $sut = new BadPropertyNamesSerializable();
        $sut->name = 'foo';

        static::assertEquals(
            '{"name":"foo"}',
            Json::serialize($sut),
        );
    }

    public function testSerializeWithChildClass(): void
    {
        $sut = new ParentClassSerializable(123, '123 Fake Address');
        $sut->name = 'foo';
        $sut->phoneNumbers = [1234, 5678];
        $sut->child = new GoodChildObjectSerializable("fubar");

        $expectedJson = <<<JSON
{
    "userName": "foo",
    "phoneNumbers": [
        1234,
        5678
    ],
    "child": {
        "childProp1": "fubar",
        "childProp2": null
    },
    "userAddress": "123 Fake Address",
    "creditCard": 123
}
JSON
            ;

        static::assertEquals(
            $expectedJson,
            Json::serialize($sut, JsonFormat::Pretty),
        );
    }

    public function testDeepNestingSerialize(): void
    {
        $sut = new ParentClassSerializable(123, '123 Fake Address');
        $sut->name = 'foo';
        $sut->phoneNumbers = [1234, 5678];
        $sut->child = new GoodChildObjectSerializable("fubar");
        $sut->child->childProperty2 = new GoodChildObjectSerializable("deep in the woods");

        $expectedJson = <<<JSON
{
    "userName": "foo",
    "phoneNumbers": [
        1234,
        5678
    ],
    "child": {
        "childProp1": "fubar",
        "childProp2": {
            "childProp1": "deep in the woods",
            "childProp2": null
        }
    },
    "userAddress": "123 Fake Address",
    "creditCard": 123
}
JSON
            ;

        static::assertEquals(
            $expectedJson,
            Json::serialize($sut, JsonFormat::Pretty),
        );
    }

    public function testEvenDeeperNestingSerialize(): void
    {
        $sut = new Nestable();

        $sut->objVal = new Nestable();
        $sut->objVal->intVal = 456;
        $sut->objVal->objVal = new Nestable();
        $sut->objVal->objVal->objVal = new Nestable();
        $sut->objVal->objVal->intVal = 999;
        $sut->objVal->objVal->objVal->objVal = new Nestable();

        $expectedJson = <<<JSON
{
    "intVal": 123,
    "stringVal": "foo",
    "objVal": {
        "intVal": 456,
        "stringVal": "foo",
        "objVal": {
            "intVal": 999,
            "stringVal": "foo",
            "objVal": {
                "intVal": 123,
                "stringVal": "foo",
                "objVal": {
                    "intVal": 123,
                    "stringVal": "foo",
                    "objVal": null
                }
            }
        }
    }
}
JSON
        ;

        static::assertEquals(
            $expectedJson,
            Json::serialize($sut, JsonFormat::Pretty),
        );
    }

    public function testWithArrays(): void
    {
        $sut = new NestableWithArray();

        $sut->arrayVal = [false, true, null, 1, 2, 3, ["a", "b"]];

        $expectedJson = <<<JSON
{
    "arrayVal": [
        false,
        true,
        null,
        1,
        2,
        3,
        [
            "a",
            "b"
        ]
    ],
    "intVal": 123,
    "stringVal": "foo",
    "objVal": null
}
JSON
        ;

        static::assertEquals(
            $expectedJson,
            Json::serialize($sut, JsonFormat::Pretty),
        );
    }

    public function testNestingWithArrays(): void
    {
        $sut = new NestableWithArray();

        $nestA = new Nestable();
        $nestA->stringVal = "arrayitem";

        $sut->arrayVal = [$nestA, 1, "fubar", true];

        $sut->objVal = new Nestable();
        $sut->objVal->intVal = 456;
        $sut->objVal->objVal = new Nestable();
        $sut->objVal->objVal->objVal = new Nestable();

        $expectedJson = <<<JSON
{
    "arrayVal": [
        {
            "intVal": 123,
            "stringVal": "arrayitem",
            "objVal": null
        },
        1,
        "fubar",
        true
    ],
    "intVal": 123,
    "stringVal": "foo",
    "objVal": {
        "intVal": 456,
        "stringVal": "foo",
        "objVal": {
            "intVal": 123,
            "stringVal": "foo",
            "objVal": {
                "intVal": 123,
                "stringVal": "foo",
                "objVal": null
            }
        }
    }
}
JSON
        ;

        static::assertEquals(
            $expectedJson,
            Json::serialize($sut, JsonFormat::Pretty),
        );
    }
}
