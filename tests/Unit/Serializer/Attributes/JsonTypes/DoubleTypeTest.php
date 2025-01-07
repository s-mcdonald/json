<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Attributes\JsonTypes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Attributes\JsonTypes\DoubleType;

#[CoversClass(DoubleType::class)]
class DoubleTypeTest extends TestCase
{
    private DoubleType $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new DoubleType();
    }

    public function testGetPhpTypeReturnsDouble(): void
    {
        static::assertSame('double', $this->sut->getPhpType());
    }

    public function testGetCompatibleCastTypesReturnsCorrectArray(): void
    {
        $expectedTypes = ['double', 'integer', 'string', 'boolean'];
        static::assertSame($expectedTypes, $this->sut->getCompatibleCastTypes());
    }

    #[DataProvider('castValueProvider')]
    public function testCastReturnsCorrectFloatValue($input, float $expected): void
    {
        $reflection = new \ReflectionClass($this->sut);
        $method = $reflection->getMethod('cast');

        static::assertSame($expected, $method->invoke($this->sut, $input));
    }

    public function castValueProvider(): array
    {
        return [
            [123, 123.0],           // Integer to float
            ['456.78', 456.78],     // String to float
            [true, 1.0],            // Boolean true to float
            [false, 0.0],           // Boolean false to float
            [null, 0.0],            // Null to float
        ];
    }
}
