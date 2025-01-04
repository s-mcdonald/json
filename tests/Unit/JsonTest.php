<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Json;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use SamMcDonald\Json\Tests\Fixtures\Entities\ClassWithPrivateStringProperty;
use SamMcDonald\Json\Tests\Fixtures\Enums\MyBackedEnum;
use SamMcDonald\Json\Tests\Fixtures\Enums\MyEnum;
use SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\ClassWithPublicStringProperty;

class JsonTest extends TestCase
{
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
}
