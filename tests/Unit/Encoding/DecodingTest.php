<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Encoding;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Json;
use SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\ClassWithPublicStringProperty;
use stdClass;

class DecodingTest extends TestCase
{
    public function testEnsureDecoderReturnsStdClass(): void
    {
        $json = '{"name":"bar"}';
        $deserializedObject = Json::deserialize($json, ClassWithPublicStringProperty::class);

        static::assertInstanceOf(
            ClassWithPublicStringProperty::class,
            $deserializedObject,
        );

        static::assertEquals(
            "bar",
            $deserializedObject->name,
        );
    }
}
