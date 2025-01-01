<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer;

use ReflectionAttribute;
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
        $classObject = new stdClass();

        $this->serializeProperties($object, $classObject);

        return $this->encoder->encode($classObject, $format)->getBody();
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
            if (1 !== count($jsonPropertyAttributes)) {
                throw new JsonSerializableException('Must have at least 1 JsonProperty Attribute.');
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
        // WIP
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

        if ($propertyValue instanceof JsonSerializable && $this->propertyReader->hasJsonPropertyAttributes($attributes)) {
            return true;
        }

        return false;
    }

    private function serializeJsonSerializableObject(JsonSerializable $propertyValue): stdClass
    {
        $classObject = new stdClass();

        $this->serializeProperties($propertyValue, $classObject);

        return $classObject;
    }
}
