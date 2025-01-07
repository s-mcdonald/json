<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Attributes\JsonTypes;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Attributes\JsonTypes\ArrayType;

class ArrayTypeTest extends TestCase
{
    private ArrayType $arrayType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->arrayType = new ArrayType();
    }

    public function testGetPhpTypeReturnsArray(): void
    {
        static::assertSame('array', $this->arrayType->getPhpType());
    }

    public function testGetCompatibleCastTypesReturnsCorrectArray(): void
    {
        $expectedTypes = ['array', 'object'];
        static::assertSame($expectedTypes, $this->arrayType->getCompatibleCastTypes());
    }

    #[DataProvider('castValueProvider')]
    public function testCastReturnsCorrectArray($input, array $expected): void
    {
        $reflection = new \ReflectionClass($this->arrayType);
        $method = $reflection->getMethod('cast');

        static::assertSame($expected, $method->invoke($this->arrayType, $input));
    }

    public static function castValueProvider(): array
    {
        return [
            [['key' => 'value'], ['key' => 'value']], // Already an array
            [(object) ['key' => 'value'], ['key' => 'value']], // Object to array
            ['string', ['string']], // String to array (wrapped in array)
            [123, [123]], // Integer to array
            [null, []], // Null to empty array
            [true, [true]], // Boolean to array
        ];
    }
}
