<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Builder;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Builder\JsonBuilder;

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

        $sut->addObjectProperty(
            "foo",
            $this->createBuilder()->addStringProperty("abc", "def")
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

        $sut->addArrayProperty("foo", ["bar"]);

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

        $sut->addStringProperty("foo", "bar");

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

        $sut->addBooleanProperty("foo", true);

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

        $sut->addNumericProperty("foo", 12345.678);

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

        $sut->addNumericProperty("foo", 12345.678);
        $sut->addNullProperty("bar");

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
