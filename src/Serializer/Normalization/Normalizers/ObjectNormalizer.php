<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalization\Normalizers;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionEnum;
use ReflectionException;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;
use SamMcDonald\Json\Builder\JsonBuilder;
use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Contracts\JsonEnum;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use SamMcDonald\Json\Serializer\Normalization\Normalizers\Context\Context;
use SamMcDonald\Json\Serializer\Normalization\Normalizers\Context\ContextBuilder;
use stdClass;
use TypeError;

final readonly class ObjectNormalizer
{
    public function __construct(
        private JsonPropertyReader $propertyReader,
    ) {
    }

    public function normalize(JsonSerializable $propertyValue): stdClass
    {
        if ($propertyValue instanceof JsonEnum) {
            return $this->normalizeEnum($propertyValue);
        }

        return $this->transferToJsonBuilder($propertyValue)->toStdClass();
    }

    private function transferToJsonBuilder(JsonSerializable $propertyValue): JsonBuilder
    {
        $jsonBuilder = new JsonBuilder();
        $contextBuilder = new ContextBuilder($this->propertyReader);

        foreach ($this->getReflectionProperties($propertyValue) as $prop) {
            assert($prop instanceof ReflectionProperty);
            $this->processProperty($contextBuilder->build($prop, $propertyValue, $jsonBuilder));
        }

        foreach ($this->getReflectionMethods($propertyValue) as $method) {
            assert($method instanceof ReflectionMethod);
            $this->processMethod($contextBuilder->build($method, $propertyValue, $jsonBuilder));
        }

        return $jsonBuilder;
    }

    private function processProperty(Context $context): void
    {
        if (0 === count($context->getJsonPropertyAttributes())) {
            return;
        }

        if (count($context->getJsonPropertyAttributes()) > 1) {
            throw new JsonSerializableException(
                sprintf(
                    'Must have only 1 %s Attribute.',
                    JsonProperty::class,
                ),
            );
        }

        if (false === $context->getReflectionItem()->isInitialized($context->getOriginalObject())) {
            throw new JsonSerializableException(
                sprintf(
                    'Value not initialized: %s',
                    $context->getPropertyName(),
                ),
            );
        }

        $propertyValue = $this->getValueFromPropOrMethod($context->getReflectionItem(), $context->getOriginalObject());

        if (false === $this->isSerializable($propertyValue, $context->getJsonPropertyAttributes())) {
            return;
        }

        $this->assignToStdClass($context->getPropertyName(), $propertyValue, $context->getClassObject());
    }

    private function processMethod(Context $context): void
    {
        if (0 === count($context->getJsonPropertyAttributes())) {
            return;
        }

        if (count($context->getJsonPropertyAttributes()) > 1) {
            throw new JsonSerializableException('Must have only 1 JsonProperty Attribute.');
        }

        $propertyValue = $this->getValueFromPropOrMethod($context->getReflectionItem(), $context->getOriginalObject());

        if (false === $this->isSerializable($propertyValue, $context->getJsonPropertyAttributes())) {
            return;
        }

        if ($context->getReflectionItem()->getNumberOfRequiredParameters() > 0) {
            throw new JsonSerializableException(
                'Can not associate JsonProperty on a function with required parameters.',
            );
        }

        $this->assignToStdClass($context->getPropertyName(), $propertyValue, $context->getClassObject());
    }

    private function assignToStdClass($propertyName, $propertyValue, JsonBuilder $classObject): void
    {
        if ($propertyValue instanceof JsonSerializable) {
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
    private function getReflectionProperties(JsonSerializable $originalObject): array
    {
        return (new ReflectionObject($originalObject))->getProperties(
            ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PRIVATE,
        );
    }

    /**
     * @return array<ReflectionMethod>
     */
    private function getReflectionMethods(JsonSerializable $originalObject): array
    {
        return (new ReflectionObject($originalObject))->getMethods(
            ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PRIVATE,
        );
    }

    /**
     * @param array<ReflectionAttribute> $attributes
     */
    private function isSerializable($propertyValue, array $attributes): bool
    {
        if (
            (null === $propertyValue || is_scalar($propertyValue) || is_array($propertyValue))
            && $this->propertyReader->hasJsonPropertyAttributes($attributes)
        ) {
            return true;
        }

        if (
            $propertyValue instanceof JsonSerializable
            && $this->propertyReader->hasJsonPropertyAttributes($attributes)
        ) {
            return true;
        }

        return false;
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
                $value instanceof JsonSerializable => $this->transferToJsonBuilder($value),
                default => throw new JsonSerializableException('Invalid type in array.'),
            };
        }

        return $newArray;
    }

    private function normalizeEnum(JsonEnum $propertyValue): stdClass
    {
        $value = $propertyValue->name;
        $reflectionClass = new ReflectionClass($propertyValue);
        if ((new ReflectionEnum($propertyValue))->isBacked()) {
            $value = $propertyValue->value;
        }

        $builder = new JsonBuilder();

        return $builder
            ->addProperty($reflectionClass->getShortName(), $value)
            ->toStdClass();
    }
}
