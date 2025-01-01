<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer;

use Exception;
use ReflectionAttribute;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;
use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;
use SamMcDonald\Json\Serializer\Encoding\Contracts\EncoderInterface;
use SamMcDonald\Json\Serializer\Encoding\JsonEncoder;
use SamMcDonald\Json\Serializer\Encoding\Validator\JsonValidator;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use stdClass;

class JsonSerializer
{
    public function __construct(
        private EncoderInterface|null $encoder = null,
        private JsonPropertyReader|null $propertyReader = null,
    ) {
        if (null === $this->encoder) {
            $this->encoder = new JsonEncoder(new JsonValidator());
        }

        if (null === $this->propertyReader) {
            $this->propertyReader = new JsonPropertyReader();
        }
    }

    public function serialize(JsonSerializable $object, JsonFormat $format): string
    {
        return $this->encoder->encode($this->serializeJsonSerializableObject($object), $format)->getBody();
    }

    private function serializeJsonSerializableObject(JsonSerializable $propertyValue): stdClass
    {
        $classObject = new stdClass();

        $this->serializeProperties($propertyValue, $classObject);
        $this->serializeMethods($propertyValue, $classObject);

        return $classObject;
    }

    private function serializeProperties(
        JsonSerializable $originalObject,
        stdClass $classObject,
    ): void {
        foreach ($this->getReflectionProperties($originalObject) as $prop) {
            $jsonPropertyAttributes = $prop->getAttributes(JsonProperty::class);
            $propertyName = $this->propertyReader->getJsonPropertyName($prop->getName(), $jsonPropertyAttributes);

            // if the property is not initialized, then we dont serialize,
            // an opportunity here to pass a config and throw error
            // at users request.
            //
            // Eg: if the JsonProperty has isRequired = true, throw error!
            if (false === $prop->isInitialized($originalObject)) {
                // if allow null for initialized
                // $classObject->{$propertyName} = null;
                continue;
            }

            if ($prop->isPrivate() || $prop->isProtected()) {
                $prop->setAccessible(true);
            }

            // we need at least one of these
            if (0 === count($jsonPropertyAttributes)) {
                continue;
            }

            if (1 !== count($jsonPropertyAttributes)) {
                throw new JsonSerializableException('Must have only 1 JsonProperty Attribute.');
            }

            $propertyValue = $prop->getValue($originalObject);
            if (false === $this->isPropertyOrMethodSerializable($propertyValue, $jsonPropertyAttributes)) {
                continue;
            }

            // if the propertyValue is a class of JsonSerializable, and has JsonProperty
            if ($propertyValue instanceof JsonSerializable) {
                $classObject->{$propertyName} = $this->serializeJsonSerializableObject($propertyValue);
                continue;
            }

            // what did we receive?
            if (false === is_scalar($propertyValue) && false === is_array($propertyValue)) {
                continue;
            }

            // @todo: handle various array values
            $classObject->{$propertyName} = $propertyValue;
        }
    }

    private function serializeMethods(
        JsonSerializable $originalObject,
        stdClass $classObject,
    ): void {
        foreach ($this->getReflectionMethods($originalObject) as $method) {
            assert($method instanceof ReflectionMethod);
            $jsonPropertyAttributes = $method->getAttributes(JsonProperty::class);
            $propertyName = $this->propertyReader->getJsonPropertyName($method->getName(), $jsonPropertyAttributes);

            // we need at least one of these
            if (0 === count($jsonPropertyAttributes)) {
                continue;
            }

            if (1 !== count($jsonPropertyAttributes)) {
                throw new JsonSerializableException('Must have only 1 JsonProperty Attribute.');
            }

            if ($method->isPrivate() || $method->isProtected()) {
                $method->setAccessible(true);
            }

            try {
                $propertyValue = $method->invoke($method->isStatic() ? null : $originalObject);
            } catch (Exception $e) {
                $propertyValue = null;
            }

            if (false === $this->isPropertyOrMethodSerializable($propertyValue, $jsonPropertyAttributes)) {
                continue;
            }

            if ($method->getNumberOfRequiredParameters() > 0) {
                throw new JsonSerializableException(
                    'Can not associate JsonProperty on a function with required parameters.',
                );
            }

            // if the propertyValue is a class of JsonSerializable, and has JsonProperty
            if ($propertyValue instanceof JsonSerializable) {
                $classObject->{$propertyName} = $this->serializeJsonSerializableObject($propertyValue);
                continue;
            }

            // what did we receive?
            if (false === is_scalar($propertyValue) && false === is_array($propertyValue)) {
                continue;
            }

            // @todo: handle various array values
            $classObject->{$propertyName} = $propertyValue;
        }
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
            $propertyValue instanceof JsonSerializable &&
            $this->propertyReader->hasJsonPropertyAttributes($attributes)
        ) {
            return true;
        }

        return false;
    }
}
