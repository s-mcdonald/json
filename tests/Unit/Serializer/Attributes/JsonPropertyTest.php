<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Attributes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Attributes\JsonTypes\StringType;

#[CoversClass(JsonProperty::class)]
class JsonPropertyTest extends TestCase
{
    public function testJsonPropertyWithNoArgs(): void
    {
        $sut = new JsonProperty();

        static::assertNull(
            $sut->getName(),
        );

        static::assertTrue(
            $sut->isNameValid(),
        );
    }

    public function testJsonPropertyWithValidName(): void
    {
        $sut = new JsonProperty("jsonPropName");

        static::assertEquals(
            "jsonPropName",
            $sut->getName()
        );

        static::assertTrue(
            $sut->isNameValid(),
        );
    }

    #[DataProvider("provideBadJsonPropertyNames")]
    public function testJsonPropertyWithBadName($badName): void
    {
        $sut = new JsonProperty($badName);

        static::assertEquals(
            $badName,
            $sut->getName()
        );

        static::assertFalse(
            $sut->isNameValid(),
        );
    }

    public static function provideBadJsonPropertyNames(): array
    {
        return [
            ["json PropName"],         // Space is not valid in unquoted strings
            ["123jsonProp"],           // Starts with a number (non-standard key usage)
            [".jsonProp"],             // Leading dot
            ["json-Prop"],             // Hyphen in a key
            ["json\$Prop"],            // Special character $
            ["json@Prop"],             // Special character @
            ["json#Prop"],             // Special character #
            ["!jsonProp"],             // Starts with special character
            ["json/Prop"],             // Contains a slash
            [" "],                     // Blank space
            ["json\tProp"],            // Tab character
            ["\b"],                    // Backspace character
            ["\"jsonProp"],            // Leading double-quote not escaped properly
            ["'jsonProp"],             // Leading single-quote not valid in JSON
            ["json\nProp"],            // Contains newline
        ];
    }

    public function testJsonPropertyWithTypeAsNull(): void
    {
        $sut = new JsonProperty();

        static::assertNull($sut->getType());
    }

    public function testJsonPropertyWithType(): void
    {
        $sut = new JsonProperty(type: new StringType());

        static::assertInstanceOf(
            StringType::class,
            $sut->getType(),
        );
    }
}
