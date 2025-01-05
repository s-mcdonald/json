<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Encoding;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Encoding\JsonEncoder;
use SamMcDonald\Json\Serializer\Encoding\JsonEncodingResult;
use SamMcDonald\Json\Serializer\Encoding\Validator\JsonValidator;

#[CoversClass(JsonEncoder::class)]
class JsonEncoderTest extends TestCase
{
    public function testDecode(): void
    {
        $encodedData = <<<JSON
{
    "foo": "bar"
}
JSON
        ;
        $decodedData = (object) ["foo" => "bar"];

        $expectation = new JsonEncodingResult(
            $encodedData,
            '',
            true,
        );

        $sut = new JsonEncoder(new JsonValidator());

        static::assertEquals(
            $expectation,
            $sut->encode($decodedData)
        );
    }
}
