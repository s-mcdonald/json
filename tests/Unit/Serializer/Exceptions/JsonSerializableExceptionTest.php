<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Exceptions;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;

#[CoversClass(JsonSerializableException::class)]
class JsonSerializableExceptionTest extends TestCase
{
    public function testJsonSerializableExceptionIsInstanceOfRuntimeException(): void
    {
        $exception = new JsonSerializableException();

        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }

    public function testHasTooManyJsonPropertiesReturnsCorrectException(): void
    {
        $exception = JsonSerializableException::hasTooManyJsonProperties();

        $this->assertInstanceOf(JsonSerializableException::class, $exception);
        $this->assertSame('Cannot serialize object with more than 1 JsonProperty', $exception->getMessage());
    }

    public function testJsonSerializableExceptionCanContainCustomMessage(): void
    {
        $message = "Custom serialization error message";
        $exception = new JsonSerializableException($message);

        $this->assertSame($message, $exception->getMessage());
    }

    public function testJsonSerializableExceptionCanContainCustomCode(): void
    {
        $code = 101;
        $exception = new JsonSerializableException("Another error", $code);

        $this->assertSame($code, $exception->getCode());
    }

    public function testJsonSerializableExceptionCanContainPreviousException(): void
    {
        $previousException = new \Exception("Previous exception");
        $exception = new JsonSerializableException("Outer exception", 0, $previousException);

        $this->assertSame($previousException, $exception->getPrevious());
    }
}
