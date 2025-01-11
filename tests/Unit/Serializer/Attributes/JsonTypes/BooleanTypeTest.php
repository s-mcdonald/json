<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Attributes\JsonTypes;

use PHPUnit\Framework\Attributes\CoversClass;
use SamMcDonald\Json\Serializer\Attributes\JsonTypes\BooleanType;
use PHPUnit\Framework\TestCase;

#[CoversClass(BooleanType::class)]
class BooleanTypeTest extends TestCase
{
    public function testGetPhpType(): void
    {
        $booleanType = new BooleanType();

        $this->assertSame('boolean', $booleanType->getPhpType());
    }

    public function testGetCompatibleCastTypes(): void
    {
        $booleanType = new BooleanType();

        $expectedTypes = ['boolean', 'integer', 'string', 'NULL'];
        $this->assertSame($expectedTypes, $booleanType->getCompatibleCastTypes());
    }

    public function testCast(): void
    {
        $booleanType = new BooleanType();

        $this->assertTrue($this->invokeCast($booleanType, true));
        $this->assertFalse($this->invokeCast($booleanType, false));
        $this->assertTrue($this->invokeCast($booleanType, 1));
        $this->assertFalse($this->invokeCast($booleanType, 0));
        $this->assertTrue($this->invokeCast($booleanType, 'true'));
        $this->assertFalse($this->invokeCast($booleanType, ''));
        $this->assertFalse($this->invokeCast($booleanType, null));
    }

    /**
     * Helper method to access the protected cast method
     * @throws \ReflectionException
     */
    private function invokeCast(BooleanType $booleanType, $value): bool
    {
        return (new \ReflectionMethod(BooleanType::class, 'cast'))->invoke($booleanType, $value);
    }
}
