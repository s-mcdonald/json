<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Normalization\Normalizers;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Normalization\Normalizers\ObjectNormalizer;

#[CoversClass(ObjectNormalizer::class)]
class ObjectNormalizerTest extends TestCase
{
    #[DataProvider('provideNormalizeMustBeObject')]
    public function testNormalizeMustBeObject($input): void
    {
        $this->expectException(InvalidArgumentException::class);
        $sut = new ObjectNormalizer(new JsonPropertyReader());
        $sut->normalize($input);
    }

    public static function provideNormalizeMustBeObject(): array
    {
        return [
            [null],
            [1],
            ["1"],
            [true],
            [12.34],
        ];
    }

    public function testNormalizeObjectWithNoJsonProperties(): void
    {
        $myObject = new class {
            public string $foo = "bar";
            public int $baz = 123;
        };

        $sut = new ObjectNormalizer(new JsonPropertyReader());
        $result = $sut->normalize($myObject);
        static::assertEquals(
            (object) [],
            $result
        );
    }

    public function testNormalizeObjectWithSingleJsonProperty(): void
    {
        $myObject = new class {
            #[JsonProperty]
            public string $foo = "bar";
            public int $baz = 123;
        };

        $sut = new ObjectNormalizer(new JsonPropertyReader());
        $result = $sut->normalize($myObject);
        static::assertEquals(
            (object) ["foo" => "bar"],
            $result
        );
    }
}
