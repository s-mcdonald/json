<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalizers;

use ReflectionAttribute;
use ReflectionException;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;
use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use SamMcDonald\Json\Serializer\Normalizers\Context\Context;
use SamMcDonald\Json\Serializer\Normalizers\Context\ContextBuilder;
use stdClass;
use TypeError;

final readonly class ObjectNormalizer
{
    public function __construct(
        private JsonPropertyReader $propertyReader,
    ) {
    }

    /**
     * Serializes a JsonSerializable object to a StdClass.
     * This is needed to normalize the values from
     * your class.
     */
    public function serializeJsonSerializableToStdObject(JsonSerializable $propertyValue): stdClass
    {
        $classObject = new stdClass();

        $contextBuilder = new ContextBuilder($this->propertyReader);

        foreach ($this->getReflectionProperties($propertyValue) as $prop) {
            assert($prop instanceof ReflectionProperty);
            $this->processProperty($contextBuilder->build($prop, $propertyValue, $classObject));
        }

        foreach ($this->getReflectionMethods($propertyValue) as $method) {
            assert($method instanceof ReflectionMethod);
            $this->processMethod($contextBuilder->build($method, $propertyValue, $classObject));
        }

        return $classObject;
    }

    private function processProperty(Context $context): void
    {
        if (0 === count($context->getJsonPropertyAttributes())) {
            return;
        }

        if (count($context->getJsonPropertyAttributes()) > 1) {
            throw new JsonSerializableException('Must have only 1 JsonProperty Attribute.');
        }

        if (false === $context->getReflectionItem()->isInitialized($context->getOriginalObject())) {
            return;
        }

        $propertyValue = $this->getValueFromPropOrMethod($context->getReflectionItem(), $context->getOriginalObject());

        if (false === $this->isPropertyOrMethodSerializable($propertyValue, $context->getJsonPropertyAttributes())) {
            return;
        }

        // what did we receive?
        //        if (
        //            !($propertyValue instanceof JsonSerializable) &&
        //            false === is_scalar($propertyValue) && false === is_array($propertyValue)
        //        ) {
        //            return;
        //        }

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

        if (false === $this->isPropertyOrMethodSerializable($propertyValue, $context->getJsonPropertyAttributes())) {
            return;
        }

        if ($context->getReflectionItem()->getNumberOfRequiredParameters() > 0) {
            throw new JsonSerializableException(
                'Can not associate JsonProperty on a function with required parameters.',
            );
        }

        // what did we receive?
        //        if (
        //            !($propertyValue instanceof JsonSerializable) &&
        //            false === is_scalar($propertyValue) && false === is_array($propertyValue)
        //        ) {
        //            return;
        //        }

        $this->assignToStdClass($context->getPropertyName(), $propertyValue, $context->getClassObject());
    }

    private function assignToStdClass($propertyName, $propertyValue, $classObject): void
    {
        if ($propertyValue instanceof JsonSerializable) {
            $classObject->{$propertyName} = $this->serializeJsonSerializableToStdObject($propertyValue);

            return;
        }

        $classObject->{$propertyName} = $propertyValue;
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
    private function isPropertyOrMethodSerializable($propertyValue, array $attributes): bool
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
        if ($reflection->isPrivate() || $reflection->isProtected()) {
            $reflection->setAccessible(true);
        }

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
}
