<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Json;
use SamMcDonald\Json\Tests\Unit\Serializer\Fixtures\ParentClassSerializable;

class SerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $sut = new ParentClassSerializable();

        static::assertEquals(
            '{}',
            Json::serialize($sut),
        );
    }
}
