<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer;

use ReflectionObject;
use ReflectionProperty;
use SamMcDonald\Json\Serializer\Encoding\Contracts\EncoderInterface;
use SamMcDonald\Json\Serializer\Encoding\JsonEncoder;
use SamMcDonald\Json\Serializer\Encoding\Validator\JsonValidator;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;
use stdClass;

class JsonSerializer
{
    public function __construct(
        private EncoderInterface|null $encoder = null,
    ) {
        if (null === $this->encoder) {
            $this->encoder = new JsonEncoder(new JsonValidator());
        }
    }

    public function serialize(Contracts\JsonSerializable $object, JsonFormat $format): string
    {
        $classObject = new stdClass();

        $this->serializeProperties($object, $classObject);

        return $this->encoder->encode($classObject, $format)->getBody();
    }

    private function serializeProperties(
        Contracts\JsonSerializable $originalObject,
        stdClass $classObject,
    ): void {
        //
        // 1. Iterate over all properties from the serializable class
        //
        foreach ($this->getReflectionProperties($originalObject) as $prop) {
        }
    }

    /**
     * @return array<ReflectionProperty>
     */
    private function getReflectionProperties(Contracts\JsonSerializable $originalObject): array
    {
        return (new ReflectionObject($originalObject))->getProperties(
            ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PRIVATE,
        );
    }
}
