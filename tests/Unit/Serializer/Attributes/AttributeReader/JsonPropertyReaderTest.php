<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Attributes\AttributeReader;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;

class JsonPropertyReaderTest extends TestCase
{
    public function testGetJsonPropertyNameWithNoAttributesReturnsDefault(): void
    {
        $sut = new JsonPropertyReader();
        $defaultName = 'default';

        $result = $sut->getJsonPropertyName($defaultName, []);

        static::assertSame($defaultName, $result);
    }

    public function testGetJsonPropertyNameWithSingleValidAttribute(): void
    {
        $reader = new JsonPropertyReader();

        $jsonPropertyMock = $this->createMock(JsonProperty::class);
        $jsonPropertyMock->method('getName')->willReturn('customName');
        $jsonPropertyMock->method('isNameValid')->willReturn(true);

        $attributeMock = $this->createMock(ReflectionAttribute::class);
        $attributeMock->method('newInstance')->willReturn($jsonPropertyMock);

        $result = $reader->getJsonPropertyName('default', [$attributeMock]);

        static::assertSame('customName', $result);
    }

    public function testGetJsonPropertyNameThrowsExceptionOnInvalidName(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $reader = new JsonPropertyReader();

        $jsonPropertyMock = $this->createMock(JsonProperty::class);
        $jsonPropertyMock->method('getName')->willReturn('invalidName');
        $jsonPropertyMock->method('isNameValid')->willReturn(false);

        $attributeMock = $this->createMock(ReflectionAttribute::class);
        $attributeMock->method('newInstance')->willReturn($jsonPropertyMock);

        $reader->getJsonPropertyName('default', [$attributeMock]);
    }

    public function testHasJsonPropertyAttributesReturnsTrue(): void
    {
        $reader = new JsonPropertyReader();

        $attributeMock = $this->createMock(ReflectionAttribute::class);
        $attributeMock->method('getName')->willReturn(JsonProperty::class);

        $result = $reader->hasJsonPropertyAttributes([$attributeMock]);

        static::assertTrue($result);
    }

    public function testHasJsonPropertyAttributesReturnsFalse(): void
    {
        $reader = new JsonPropertyReader();

        $attributeMock = $this->createMock(ReflectionAttribute::class);
        $attributeMock->method('getName')->willReturn('OtherAttribute');

        $result = $reader->hasJsonPropertyAttributes([$attributeMock]);

        static::assertFalse($result);
    }

    public function testFindMethodWithJsonPropertyReturnsCorrectMethod(): void
    {
        $reader = new JsonPropertyReader();

        $jsonPropertyMock = $this->createMock(JsonProperty::class);
        $jsonPropertyMock->method('getName')->willReturn('expectedPropertyName');
        $jsonPropertyMock->method('isNameValid')->willReturn(true);

        $attributeMock = $this->createMock(ReflectionAttribute::class);
        $attributeMock->method('newInstance')->willReturn($jsonPropertyMock);

        $methodMock = $this->createMock(ReflectionMethod::class);
        $methodMock->method('getName')->willReturn('expectedPropertyName');
        $methodMock->method('getAttributes')->willReturn([$attributeMock]);

        $reflectionClassMock = $this->createMock(ReflectionClass::class);
        $reflectionClassMock->method('getMethods')->willReturn([$methodMock]);

        $result = $reader->findMethodWithJsonProperty($reflectionClassMock, 'expectedPropertyName');

        $this->assertSame($methodMock, $result);
    }

    public function testFindMethodWithJsonPropertyReturnsNullWhenNoMethodMatches(): void
    {
        $reader = new JsonPropertyReader();

        $methodMock = $this->createMock(ReflectionMethod::class);
        $methodMock->method('getAttributes')->willReturn([]);

        $reflectionClassMock = $this->createMock(ReflectionClass::class);
        $reflectionClassMock->method('getMethods')->willReturn([$methodMock]);

        $result = $reader->findMethodWithJsonProperty($reflectionClassMock, 'nonExistingPropertyName');

        $this->assertNull($result);
    }

    public function testFindPropertyByAttributeWithArgumentReturnsCorrectProperty(): void
    {
        $reader = new JsonPropertyReader();

        $attributeMock = $this->createMock(ReflectionAttribute::class);
        $attributeMock
            ->method('getArguments')
            ->willReturn(['argumentName' => 'expectedPropertyName']);

        $propertyMock = $this->createMock(ReflectionProperty::class);
        $propertyMock->method('getAttributes')->willReturn([$attributeMock]);

        $reflectionClassMock = $this->createMock(ReflectionClass::class);
        $reflectionClassMock->method('getProperties')->willReturn([$propertyMock]);

        $result = $reader->findPropertyByAttributeWithArgument($reflectionClassMock, 'argumentName', 'expectedPropertyName');

        $this->assertSame($propertyMock, $result);
    }

    public function testFindPropertyByAttributeWithArgumentReturnsNullWhenNoMatch(): void
    {
        $reader = new JsonPropertyReader();

        $attributeMock = $this->createMock(ReflectionAttribute::class);
        $attributeMock
            ->method('getArguments')
            ->willReturn(['argumentName' => 'someOtherPropertyName']);

        $propertyMock = $this->createMock(ReflectionProperty::class);
        $propertyMock->method('getAttributes')->willReturn([$attributeMock]);

        $reflectionClassMock = $this->createMock(ReflectionClass::class);
        $reflectionClassMock->method('getProperties')->willReturn([$propertyMock]);

        $result = $reader->findPropertyByAttributeWithArgument($reflectionClassMock, 'argumentName', 'expectedPropertyName');

        $this->assertNull($result);
    }
}
