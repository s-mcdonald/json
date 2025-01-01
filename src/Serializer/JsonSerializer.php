<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer;

use ReflectionObject;
use SamMcDonald\Json\Serializer\Encoding\Contracts\EncoderInterface;
use SamMcDonald\Json\Serializer\Encoding\JsonEncoder;
use SamMcDonald\Json\Serializer\Encoding\Validator\JsonValidator;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;
use stdClass;

class JsonSerializer
{
    public function __construct(
        private EncoderInterface|null $encoder,
    ) {
        if (null === $this->encoder) {
            $this->encoder = new JsonEncoder(new JsonValidator());
        }
    }

    public function serialize(Contracts\JsonSerializable $object, JsonFormat $format): string
    {
        $classObject = new stdClass();
        $reflectionScope = new ReflectionObject($object);

        $this->serializeProperties($reflectionScope, $object, $classObject);

        return $this->encoder->encode($classObject, $format)->getBody();
    }

    private function serializeProperties(
        ReflectionObject $reflectionScope,
        Contracts\JsonSerializable $object,
        stdClass $classObject,
    ): void {
    }
}
