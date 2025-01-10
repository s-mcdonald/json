<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Encoding;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Encoding\JsonDecoder;
use SamMcDonald\Json\Serializer\Encoding\JsonEncodingResult;

#[CoversClass(JsonDecoder::class)]
class JsonDecoderTest extends TestCase
{
    public function testDecode(): void
    {
        $decodedData = (object) ["foo" => "bar"];
        $expectation = new JsonEncodingResult(
            $decodedData,
            '',
            true,
        );

        $sut = new JsonDecoder();

        static::assertEquals(
            $expectation,
            $sut->decode('{"foo":"bar"}')
        );
    }
}
