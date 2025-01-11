<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Facets;

use SamMcDonald\Json\Tests\Fixtures\Entities\ClassesWithCreatesFromJson\MyClassWithCreatesFromJson;
use PHPUnit\Framework\Attributes\CoversClass;
use SamMcDonald\Json\Serializer\Facets\CreatesFromJson;
use PHPUnit\Framework\TestCase;

#[CoversClass(CreatesFromJson::class)]
class CreatesFromJsonTest extends TestCase
{
    public function testCreateFromJson(): void
    {
        $json = '{"name":"John Doe","age":30}';

        $testClass = MyClassWithCreatesFromJson::createFromJson($json);

        static::assertInstanceOf(
            MyClassWithCreatesFromJson::class,
            $testClass,
        );

        static::assertEquals(
            30,
            $testClass->age,
        );

        static::assertEquals(
            'John Doe',
            $testClass->name,
        );
    }
}
