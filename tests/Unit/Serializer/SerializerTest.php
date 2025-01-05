<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Json;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;
use SamMcDonald\Json\Tests\Fixtures\Entities\GoodChildObjectSerializable;
use SamMcDonald\Json\Tests\Fixtures\Entities\NestingClasses\Nestable;
use SamMcDonald\Json\Tests\Fixtures\Entities\NestingClasses\NestableWithArray;
use SamMcDonald\Json\Tests\Fixtures\Entities\NoAttributeClasses\SimpleScalaProperties;
use SamMcDonald\Json\Tests\Fixtures\Entities\ParentClassSerializable;
use SamMcDonald\Json\Tests\Fixtures\Entities\SimplePropertyClass;

/**
 * Move all these tests to JsonTest.
 */
class SerializerTest extends TestCase
{
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

    public function testHydrateToAlternateProperty(): void
    {
        $expected = new SimpleScalaProperties();
        $expected->name = 'Freddy';
        $expected->age = 35;
        $expected->isActive = true;

        $json = <<<JSON
{
  "name": "Freddy", 
  "age": 35, 
  "isActive": true
}
JSON
            ;

        static::assertEquals(
            $expected,
            Json::deserialize($json, SimpleScalaProperties::class),
        );
    }

    public function testSimpleHydration2(): void
    {
        $expected = new SimplePropertyClass("myusername", 44);

        $json = <<<JSON
{
  "userName": "myusername", 
  "age": 44
}
JSON
        ;

        $hydrated = Json::deserialize($json, SimplePropertyClass::class);
        assert($hydrated instanceof SimplePropertyClass);

        static::assertEquals(
            $expected,
            $hydrated,
        );

        static::assertEquals(
            'myusername',
            $hydrated->getName(),
        );

        static::assertEquals(
            44,
            $hydrated->getAge(),
        );
    }
}
