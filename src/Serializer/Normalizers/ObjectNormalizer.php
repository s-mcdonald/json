<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalizers;

use ReflectionAttribute;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;
use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use stdClass;
use TypeError;

final class ObjectNormalizer
{
    public function __construct(
        private JsonPropertyReader|null $propertyReader = null,
    ) {
        if (null === $this->propertyReader) {
            $this->propertyReader = new JsonPropertyReader();
        }
    }

    /**
     * Serializes a JsonSerializable object to a StdClass.
     * This is needed to normalize the values from
     * your class.
     */
    public function serializeJsonSerializableToStdObject(JsonSerializable $propertyValue): stdClass
    {
        $classObject = new stdClass();

        foreach ($this->getReflectionProperties($propertyValue) as $prop) {
            assert($prop instanceof ReflectionProperty);
            $jsonPropertyAttributes = $prop->getAttributes(JsonProperty::class);
            $propertyName = $this->propertyReader->getJsonPropertyName($prop->getName(), $jsonPropertyAttributes);
            $this->processProperty($prop, $propertyValue, $classObject, $jsonPropertyAttributes, $propertyName);
        }

        foreach ($this->getReflectionMethods($propertyValue) as $method) {
            assert($method instanceof ReflectionMethod);
            $jsonPropertyAttributes = $method->getAttributes(JsonProperty::class);
            $propertyName = $this->propertyReader->getJsonPropertyName($method->getName(), $jsonPropertyAttributes);
            $this->processMethod($method, $propertyValue, $classObject, $jsonPropertyAttributes, $propertyName);
        }

        return $classObject;
    }

    private function processProperty(
        ReflectionProperty $prop,
        JsonSerializable $originalObject,
        stdClass $classObject,
        $jsonPropertyAttributes,
        $propertyName,
    ): void {
        if (0 === count($jsonPropertyAttributes)) {
            return;
        }

        if (count($jsonPropertyAttributes) > 1) {
            throw new JsonSerializableException('Must have only 1 JsonProperty Attribute.');
        }

        if (false === $prop->isInitialized($originalObject)) {
            return;
        }

        $propertyValue = $this->getValueFromPropOrMethod($prop, $originalObject);

        if (false === $this->isPropertyOrMethodSerializable($propertyValue, $jsonPropertyAttributes)) {
            return;
        }

        // what did we receive?
        //        if (
        //            !($propertyValue instanceof JsonSerializable) &&
        //            false === is_scalar($propertyValue) && false === is_array($propertyValue)
        //        ) {
        //            return;
        //        }

        $this->assignToStdClass($propertyName, $propertyValue, $classObject);
    }

    private function processMethod(
        ReflectionMethod $method,
        JsonSerializable $originalObject,
        stdClass $classObject,
        $jsonPropertyAttributes,
        $propertyName,
    ): void {
        if (0 === count($jsonPropertyAttributes)) {
            return;
        }

        if (count($jsonPropertyAttributes) > 1) {
            throw new JsonSerializableException('Must have only 1 JsonProperty Attribute.');
        }

        $propertyValue = $this->getValueFromPropOrMethod($method, $originalObject);

        if (false === $this->isPropertyOrMethodSerializable($propertyValue, $jsonPropertyAttributes)) {
            return;
        }

        if ($method->getNumberOfRequiredParameters() > 0) {
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

        $this->assignToStdClass($propertyName, $propertyValue, $classObject);
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
            (is_scalar($propertyValue) || is_array($propertyValue))
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

        if ($reflection instanceof ReflectionMethod) {
            try {
                $propertyValue = $reflection->invoke($reflection->isStatic() ? null : $originalObject);
            } catch (TypeError $t) {
                throw new JsonSerializableException('Value has not been initialized.');
            }

            return $propertyValue;
        }

        $propertyValue = $reflection->getValue($reflection->isStatic() ? null : $originalObject);

        return $propertyValue;
    }
}
