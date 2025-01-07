<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Attributes\JsonTypes;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Attributes\JsonTypes\StringType;

#[CoversClass(StringType::class)]
class StringTypeTest extends TestCase
{
    public function testStringType(): void
    {
        $sut = new StringType();

        static::assertEquals(
            '123',
            $sut->casts(123)
        );

        static::assertEquals(
            '123.456',
            $sut->casts(123.456)
        );

        static::assertEquals(
            '',
            $sut->casts(null)
        );

        static::assertEquals(
            '1',
            $sut->casts(true)
        );

        static::assertEquals(
            '',
            $sut->casts(false)
        );
    }

    public function testInValidTypesReturnOriginalValue(): void
    {
        $sut = new StringType();

        static::assertEquals(
            new \stdClass(),
            $sut->casts(new \stdClass())
        );

        static::assertEquals(
            [12,34,56],
            $sut->casts([12,34,56])
        );
    }
}
