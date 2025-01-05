<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Builder;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Builder\JsonBuilder;

#[CoversClass(JsonBuilder::class)]
class JsonBuilderTest extends TestCase
{
    public function testAddObjectProperty(): void
    {
        $sut = $this->createBuilder();
        $expected = <<<JSON
{
    "foo": {
        "abc": "def"
    }
}
JSON;

        $sut->addProperty(
            "foo",
            $this->createBuilder()->addProperty("abc", "def")
        );

        self::assertEquals(
            $expected,
            ((string) $sut)
        );
    }

    public function testAddArrayProperty(): void
    {
        $sut = $this->createBuilder();

        $expected = <<<JSON
{
    "foo": [
        "bar"
    ]
}
JSON;

        $sut->addProperty("foo", ["bar"]);

        self::assertEquals(
            $expected,
            ((string) $sut)
        );
    }

    public function testAddStringProperty(): void
    {
        $sut = $this->createBuilder();

        $expected = <<<JSON
{
    "foo": "bar"
}
JSON;

        $sut->addProperty("foo", "bar");

        self::assertEquals(
            $expected,
            ((string) $sut)
        );
    }

    public function testAddBooleanProperty(): void
    {
        $sut = $this->createBuilder();

        $expected = <<<JSON
{
    "foo": true
}
JSON;

        $sut->addProperty("foo", true);

        self::assertEquals(
            $expected,
            ((string) $sut)
        );
    }

    public function testAddNumericProperty(): void
    {
        $sut = $this->createBuilder();

        $expected = <<<JSON
{
    "foo": 12345.678
}
JSON;

        $sut->addProperty("foo", 12345.678);

        self::assertEquals(
            $expected,
            ((string) $sut)
        );
    }

    public function testAddNullProperty(): void
    {
        $sut = $this->createBuilder();

        $expected = <<<JSON
{
    "foo": 12345.678,
    "bar": null
}
JSON;

        $sut->addProperty("foo", 12345.678);
        $sut->addProperty("bar", null);

        self::assertEquals(
            $expected,
            ((string) $sut)
        );
    }

    private function createBuilder(): JsonBuilder
    {
        return new JsonBuilder();
    }
}
