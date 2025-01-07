<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Schema;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Schema\PropertyName;

class PropertyTest extends TestCase
{
    public function testDoesNotThrowExceptionWithGoodName(): void
    {
        $thrown = false;

        try {
            new PropertyName("foo");
        } catch (\Throwable $e) {
            $thrown = true;
        }

        if (false === $thrown) {
            static::assertTrue(true);
        }
    }

    public function testThrowExceptionWithBadName(): void
    {
        $thrown = false;

        try {
            new PropertyName("foo bar");
        } catch (\Throwable $e) {
            $thrown = true;
        }

        if ($thrown) {
            static::assertTrue(true);
        } else {
            static::fail();
        }
    }
}
