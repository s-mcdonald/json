<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Attributes\JsonTypes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Attributes\JsonTypes\ObjectType;

#[CoversClass(ObjectType::class)]
class ObjectTypeTest extends TestCase
{
    public function testGetPhpType(): void
    {
        $sut = new ObjectType();
        $result = $sut->getPhpType();
        static::assertSame('object', $result, "Expected 'object' as the PHP type.");
    }

    public function testGetCompatibleCastTypes(): void
    {
        $sut = new ObjectType();
        $result = $sut->getCompatibleCastTypes();
        $expected = ['object', 'array'];

        static::assertSame($expected, $result, "Expected ['object', 'array'] as compatible cast types.");
    }

    public function testCastWithArray(): void
    {
        $sut = new ObjectType();
        $value = ['key' => 'value'];
        $result = $this->invokeProtectedCast($sut, $value);

        static::assertIsObject($result, 'Expected result to be an object after casting.');
        static::assertSame('value', $result->key, 'Expected object key to match original array value.');
    }

    public function testCastWithEmptyArray(): void
    {
        $sut = new ObjectType();
        $value = [];
        $result = $this->invokeProtectedCast($sut, $value);

        static::assertIsObject($result, 'Expected result to be an object after casting an empty array.');
        static::assertEmpty((array) $result, 'Expected object to remain empty when casting from an empty array.');
    }

    public function testCastWithObject(): void
    {
        $sut = new ObjectType();
        $value = (object) ['key' => 'value'];
        $result = $this->invokeProtectedCast($sut, $value);

        $this->assertIsObject($result, 'Expected result to be an object after casting.');
        $this->assertEquals($value, $result, 'Expected object to remain unchanged when casting from an object.');
    }

    private function invokeProtectedCast(ObjectType $sut, $value): object
    {
        $reflection = new \ReflectionClass(ObjectType::class);
        return $reflection->getMethod('cast')->invoke($sut, $value);
    }
}
