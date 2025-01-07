<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Schema\Rules\Exceptions;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Schema\Rules\Exceptions\JsonTypeException;

class JsonTypeExceptionTest extends TestCase
{
    public function testJsonTypeException(): void
    {
        try {
            throw new JsonTypeException('foo');
        } catch (JsonTypeException $e) {
            $this->assertEquals('foo', $e->getMessage());
        }
    }
}
