<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Attributes\JsonTypes\DoubleType;
use SamMcDonald\Json\Serializer\Attributes\JsonTypes\IntegerType;
use SamMcDonald\Json\Serializer\Attributes\JsonTypes\StringType;
use SamMcDonald\Json\Serializer\JsonSerializer;
use SamMcDonald\Json\Tests\Fixtures\Entities\NoAttributeClasses\SimpleScalaProperties;

#[CoversClass(JsonSerializer::class)]
#[UsesClass(SimpleScalaProperties::class)]
class JsonSerializerTest extends TestCase
{
    public function testConstruct(): void
    {
        $sut = new JsonSerializer();

        static::assertInstanceOf(JsonSerializer::class, $sut);
    }

    public function testSerializeWithPrivateProperties(): void
    {
        $sut = new JsonSerializer();

        $objectToSerialize = new class {
            #[JsonProperty]
            private int $number = 1234;
            #[JsonProperty]
            public string $name = 'foo';
        };

        static::assertEquals(
            '{"number":1234,"name":"foo"}',
            $sut->serialize($objectToSerialize)
        );
    }

    #[DataProvider('providerForSerializeWithTypedProperties')]
    public function testSerializeWithTypedProperties(string $expected, object $objectToSerialize): void
    {
        $sut = new JsonSerializer();

        static::assertEquals(
            $expected,
            $sut->serialize($objectToSerialize)
        );
    }

    public static function providerForSerializeWithTypedProperties(): array
    {
        return [
            'cast to IntegerType' => [
                '{"foo":"john smith","bar":123}',
                new class {
                    #[JsonProperty('foo')]
                    private string $name = 'john smith';
                    #[JsonProperty('bar', new IntegerType())]
                    public float $age = 123.456;
                }
            ],
            'cast to StringType' => [
                '{"bar":"123.456"}',
                new class {
                    #[JsonProperty('bar', new StringType())]
                    public float $age = 123.456;
                }
            ],
            'cast to own self type' => [
                '{"bar":123.456}',
                new class {
                    #[JsonProperty('bar', new DoubleType())]
                    public float $age = 123.456;
                }
            ],
            'cast string to DoubleType' => [
                '{"bar":123.456}',
                new class {
                    #[JsonProperty('bar', new DoubleType())]
                    public string $age = "123.456";
                }
            ],
        ];
    }

    public function testDeserialize(): void
    {
        $sut = new JsonSerializer();

        $json = '{"age":1234,"name":"foo","isActive":true}';

        $hydrated = $sut->deserialize($json, SimpleScalaProperties::class);

        static::assertEquals(1234, $hydrated->age);
        static::assertEquals("foo", $hydrated->name);
        static::assertEquals(true, $hydrated->isActive);
    }
}
