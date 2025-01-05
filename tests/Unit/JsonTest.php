<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Json;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use SamMcDonald\Json\Tests\Fixtures\Entities\ClassWithMethodAndConstructor;
use SamMcDonald\Json\Tests\Fixtures\Entities\ClassWithPrivateStringProperty;
use SamMcDonald\Json\Tests\Fixtures\Entities\ClassWithPublicStringProperty;
use SamMcDonald\Json\Tests\Fixtures\Enums\MyBackedEnum;
use SamMcDonald\Json\Tests\Fixtures\Enums\MyEnum;

class JsonTest extends TestCase
{
    /**
     * When a bad name is presented in the JsonProperty we should throw an exception
     * to alert the developer|user to fix or resolve the issue.
     * @dataProvider provideDataForBadPropertyName
     */
    public function testSerializeWithBadPropertyNameCausesException($badPropertyNameObject): void
    {
        $this->expectException(InvalidArgumentException::class);

        $sut = $badPropertyNameObject;
        $sut->badProperty = 'foo';

        static::assertEquals(
            '{"name":"foo"}',
            Json::serialize($sut),
        );
    }

    public static function provideDataForBadPropertyName(): array
    {
        return [
            'space in property name' => [
                new class
                {
                    #[JsonProperty('user Name')]
                    public string $badProperty;
                }
            ],
            'minus in property name' => [
                new class
                {
                    #[JsonProperty('-')]
                    public string $badProperty;
                }
            ],
            'equal sign in property name' => [
                new class
                {
                    #[JsonProperty('=')]
                    public string $badProperty;
                }
            ],
        ];
    }

    public function testSerializeWithConstructorPrivatePropertySerialized(): void
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
     * This test SHOULD throw an exception, as we have explicitly declared that a specific
     * property should be serialized. If the property is not annotated with
     * JsonProperty, it would otherwise proceed silently.
     */
    public function testSerializeWithUninitializedPropertyCausesException(): void
    {
        $this->expectException(JsonSerializableException::class);
        $this->expectExceptionMessage('Value not initialized');

        $sut = new ClassWithPublicStringProperty();

        Json::serialize($sut);
    }

    public function testSimpleSerializationOfPublicStringProperty(): void
    {
        $sut = new ClassWithPublicStringProperty();
        $sut->name = 'bar';

        static::assertEquals(
            '{"name":"bar"}',
            Json::serialize($sut),
        );
    }

    public function testSimpleSerializationOfPrivateStringProperty(): void
    {
        $sut = new ClassWithPrivateStringProperty('fubar');

        static::assertEquals(
            '{"name":"fubar"}',
            Json::serialize($sut),
        );
    }

    /**
     * This test will compare the serialized result of a BackedEnum.
     * Backed enums are serialized with their backing value.
     */
    public function testSerializeBackedEnumTest(): void
    {
        $foo = MyBackedEnum::Foo;

        static::assertEquals(
            '{"MyBackedEnum":"foo"}',
            Json::serialize($foo),
        );
    }

    /**
     * This test will compare the serialized result of a Pure Enum.
     * Pure enums are serialized with their constant name.
     */
    public function testSerializePureEnumTest(): void
    {
        $foo = MyEnum::foo;

        static::assertEquals(
            '{"MyEnum":"foo"}',
            Json::serialize($foo),
        );
    }

    /**
     * Test the formatting on the Json::facade
     */
    public function testPrettify(): void
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

    /**
     * Test the formatting on the Json::facade
     */
    public function testUglify(): void
    {
        $jsonUgly = '{"name":"bar"}';

        $jsonPretty = <<<JSON
{
    "name": "bar"
}
JSON
        ;

        static::assertEquals(
            $jsonUgly,
            Json::uglify($jsonPretty),
        );
    }

    public function testToArray(): void
    {
        $json = '{"name":"bar","age":19, "isActive":true, "children": [{"name":"child1"},{"name":"child2"}]}';

        $array = Json::toArray($json);

        static::assertIsArray($array);
        static::assertCount(4, $array);
        static::assertEquals('bar', $array['name']);
        static::assertEquals(19, $array['age']);
        static::assertEquals(true, $array['isActive']);
        static::assertIsArray($array['children']);
        static::assertCount(2, $array['children']);
        static::assertEquals('child1', $array['children'][0]['name']);
        static::assertEquals('child2', $array['children'][1]['name']);
    }

    /**
     * With malformed json string, false is expected to be returned
     */
    public function testToArrayWithBadJson(): void
    {
        $json = '{"name":"bar","age":19, "isActive":true, "children": [{"name":"child1"},{"name":"child2"}';
        $array = Json::toArray($json);
        static::assertFalse($array);
    }
}
