<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Exceptions;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Exceptions\JsonException;

#[CoversClass(JsonException::class)]
class JsonExceptionTest extends TestCase
{
    public function testJsonExceptionIsInstanceOfRuntimeException(): void
    {
        $exception = new JsonException();

        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }

    public function testJsonExceptionCanContainCustomMessage(): void
    {
        $message = "Custom error message";
        $exception = new JsonException($message);

        $this->assertSame($message, $exception->getMessage());
    }

    public function testJsonExceptionCanContainCustomCode(): void
    {
        $code = 12345;
        $exception = new JsonException("Some message", $code);

        $this->assertSame($code, $exception->getCode());
    }

    public function testJsonExceptionContainsPreviousException(): void
    {
        $previousException = new \Exception("Previous exception message");
        $exception = new JsonException("Outer exception", 0, $previousException);

        $this->assertSame($previousException, $exception->getPrevious());
    }
}
