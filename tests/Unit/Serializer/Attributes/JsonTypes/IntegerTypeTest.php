<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Attributes\JsonTypes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Attributes\JsonTypes\IntegerType;

#[CoversClass(IntegerType::class)]
class IntegerTypeTest extends TestCase
{
    private IntegerType $integerType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->integerType = new IntegerType();
    }

    public function testGetPhpTypeReturnsInteger(): void
    {
        $this->assertSame('integer', $this->integerType->getPhpType());
    }

    public function testGetCompatibleCastTypesReturnsCorrectArray(): void
    {
        $expectedTypes = ['double', 'integer', 'string', 'boolean'];
        $this->assertSame($expectedTypes, $this->integerType->getCompatibleCastTypes());
    }

    #[DataProvider('castValueProvider')]
    public function testCastReturnsCorrectIntegerValue($input, int $expected): void
    {
        $reflection = new \ReflectionClass($this->integerType);
        $method = $reflection->getMethod('cast');

        $this->assertSame($expected, $method->invoke($this->integerType, $input));
    }

    public static function castValueProvider(): array
    {
        return [
            [123.45, 123],
            ['456', 456],
            [true, 1],
            [false, 0],
            [null, 0],
        ];
    }
}
