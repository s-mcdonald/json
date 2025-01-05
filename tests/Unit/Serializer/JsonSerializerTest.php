<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
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
