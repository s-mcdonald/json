<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Hydration\Exceptions;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Serializer\Hydration\Exceptions\HydrationException;

#[CoversClass(HydrationException::class)]
class HydrationExceptionTest extends TestCase
{
    public function testCreateHydrationParseException(): void
    {
        $exception = HydrationException::createHydrationParseException();

        static::assertInstanceOf(HydrationException::class, $exception);
        static::assertSame('Unable to parse hydration data.', $exception->getMessage());
    }

    public function testCreateHydrationParseTypeException(): void
    {
        $jsonType = 'string';
        $propertyType = 'int';
        $exception = HydrationException::createHydrationParseTypeException($jsonType, $propertyType);

        static::assertInstanceOf(HydrationException::class, $exception);
        static::assertSame(
            "Unable to parse hydration data: Expected {$jsonType} but got {$propertyType}",
            $exception->getMessage()
        );
    }

    public function testCreateHydrationParseWithBadPropertyNameException(): void
    {
        $exception = HydrationException::createHydrationParseWithBadPropertyNameException();

        static::assertInstanceOf(HydrationException::class, $exception);
        static::assertSame('Unable to parse hydration data: Bad Property name', $exception->getMessage());
    }

    public function testCreateMethodHasTooManyJsonProperties(): void
    {
        $methodName = 'exampleMethod';
        $exception = HydrationException::createMethodHasTooManyJsonProperties($methodName);

        static::assertInstanceOf(HydrationException::class, $exception);
        static::assertSame(
            "Method {$methodName} has too many json properties.",
            $exception->getMessage()
        );
    }

    public function testCreateTooManyRequiredParameters(): void
    {
        $methodName = 'exampleGetter';
        $exception = HydrationException::createTooManyRequiredParameters($methodName);

        static::assertInstanceOf(HydrationException::class, $exception);
        static::assertSame(
            "Method {$methodName} has too many required parameters.",
            $exception->getMessage()
        );
    }

    public function testUnknownErrorWhileHydrating(): void
    {
        $exception = HydrationException::unknownErrorWhileHydrating();

        static::assertInstanceOf(HydrationException::class, $exception);
        static::assertSame(
            'An unknown error occurred while hydrating.',
            $exception->getMessage()
        );
    }

    public function testCreateClassNotExist(): void
    {
        $fqClassName = 'NonExistentClass';
        $exception = HydrationException::createClassNotExist($fqClassName);

        static::assertInstanceOf(HydrationException::class, $exception);
        static::assertSame(
            "Unable to hydrate data: Class {$fqClassName} does not exist",
            $exception->getMessage()
        );
    }
}
