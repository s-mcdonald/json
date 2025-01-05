<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalization\Normalizers;

use InvalidArgumentException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionEnum;
use ReflectionException;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;
use SamMcDonald\Json\Builder\JsonBuilder;
use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use SamMcDonald\Json\Serializer\Normalization\Normalizers\Context\Context;
use SamMcDonald\Json\Serializer\Normalization\Normalizers\Context\ContextBuilder;
use SamMcDonald\Json\Serializer\Normalization\Normalizers\Contracts\NormalizerInterface;
use stdClass;
use TypeError;

/**
 * Normalize from object to stdClass.
 */
final readonly class EntityNormalizer implements NormalizerInterface
{
    public function __construct(
        private JsonPropertyReader $propertyReader,
        private array|null $mapping = null,
    ) {
    }

    public function normalize(mixed $input): stdClass
    {
        if (false === is_object($input)) {
            throw new InvalidArgumentException('input must be an object.');
        }

        $reflectionClass = new ReflectionClass($input);
        if ($reflectionClass->isEnum()) {
            return $this->normalizeEnum($input);
        }

        return $this->transferToJsonBuilder($input)->toStdClass();
    }

    private function transferToJsonBuilder(object $propertyValue): JsonBuilder
    {
        if (null === $this->mapping) {
            return $this->mapWithReflectionOnly($propertyValue);
        }

        // now map with mapping
        $jsonBuilder = new JsonBuilder();
        $contextBuilder = new ContextBuilder($this->propertyReader);
        foreach ($this->mapping as $property => $value) {
        }

        return $jsonBuilder;
    }

    private function mapWithReflectionOnly(object $propertyValue): JsonBuilder
    {
        $jsonBuilder = new JsonBuilder();
        $contextBuilder = new ContextBuilder($this->propertyReader);

        foreach ($this->getReflectionProperties($propertyValue) as $prop) {
            assert($prop instanceof ReflectionProperty);
            $this->processProperty($contextBuilder->build($prop, $propertyValue, $jsonBuilder));
        }

        return $jsonBuilder;
    }

    private function processProperty(Context $context): void
    {
        if (false === $context->getReflectionItem()->isInitialized($context->getOriginalObject())) {
            return;
        }

        $propertyValue = $this->getValueFromPropOrMethod($context->getReflectionItem(), $context->getOriginalObject());

        if (false === $this->isSerializable($propertyValue, $context->getJsonPropertyAttributes())) {
            return;
        }

        $this->assignToStdClass($context->getPropertyName(), $propertyValue, $context->getClassObject());
    }

    /**
     * @param array<ReflectionAttribute> $attributes
     */
    private function isSerializable($propertyValue, array $attributes): bool
    {
        return null === $propertyValue
            || is_scalar($propertyValue)
            || is_array($propertyValue)
            || is_object($propertyValue);
    }

    private function assignToStdClass($propertyName, $propertyValue, JsonBuilder $classObject): void
    {
        if (is_object($propertyValue)) {
            $classObject->addProperty($propertyName, $this->transferToJsonBuilder($propertyValue));

            return;
        }

        match (\gettype($propertyValue)) {
            'array' => $classObject->addProperty($propertyName, $this->mapArrayContents($propertyValue)),
            'NULL', 'boolean', 'string', 'integer', 'double' => $classObject->addProperty($propertyName, $propertyValue),
            default => throw new JsonSerializableException('Invalid type.'),
        };
    }

    /**
     * @return array<ReflectionProperty>
     */
    private function getReflectionProperties(object $originalObject): array
    {
        return (new ReflectionObject($originalObject))->getProperties(
            ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PRIVATE,
        );
    }

    private function getValueFromPropOrMethod(
        ReflectionMethod|ReflectionProperty $reflection,
        $originalObject,
    ): mixed {
        if (false === ($reflection instanceof ReflectionMethod)) {
            return $reflection->getValue($reflection->isStatic() ? null : $originalObject);
        }

        try {
            $propertyValue = $reflection->invoke($reflection->isStatic() ? null : $originalObject);
        } catch (TypeError $t) {
            throw new JsonSerializableException('Value has not been initialized.');
        } catch (ReflectionException $e) {
            throw new JsonSerializableException($e->getMessage());
        }

        return $propertyValue;
    }

    private function mapArrayContents(array $array): array
    {
        $newArray = [];
        foreach ($array as $value) {
            $newArray[] = match (true) {
                is_null($value) => null,
                is_bool($value), is_scalar($value) => $value,
                is_array($value) => $this->mapArrayContents($value),
                is_object($value) => $this->transferToJsonBuilder($value),
                default => throw new JsonSerializableException('Invalid type in array.'),
            };
        }

        return $newArray;
    }

    private function normalizeEnum(object $propertyValue): stdClass
    {
        $reflectionClass = new ReflectionClass($propertyValue);

        $value = $propertyValue->name;
        if ((new ReflectionEnum($propertyValue))->isBacked()) {
            $value = $propertyValue->value;
        }

        $builder = new JsonBuilder();

        return $builder
            ->addProperty($reflectionClass->getShortName(), $value)
            ->toStdClass();
    }
}
