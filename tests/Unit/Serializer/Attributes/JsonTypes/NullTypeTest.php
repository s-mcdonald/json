<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Attributes\JsonTypes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Attributes\JsonTypes\NullType;

#[CoversClass(NullType::class)]
class NullTypeTest extends TestCase
{
    private NullType $nullType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->nullType = new NullType();
    }

    public function testGetPhpType(): void
    {
        $result = $this->nullType->getPhpType();
        static::assertSame('NULL', $result, "Expected 'NULL' as the PHP type.");
    }

    public function testGetCompatibleCastTypes(): void
    {
        $result = $this->nullType->getCompatibleCastTypes();
        $expected = ['NULL', 'boolean'];

        static::assertSame($expected, $result, "Expected ['NULL', 'boolean'] as compatible cast types.");
    }

    public function testCastAlwaysReturnsNull(): void
    {
        $value = 'any value';
        $result = $this->invokeProtectedCast($value);

        static::assertNull($result, 'Expected the protected cast method to always return null.');
    }

    private function invokeProtectedCast($value): null
    {
        $reflection = new \ReflectionClass(NullType::class);
        return $reflection->getMethod('cast')->invoke($this->nullType, $value);
    }
}
