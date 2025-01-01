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
    // Allow custom encoders but default to
    // the libs preferred
    public function __construct(
        private EncoderInterface|null $encoder,
    )
    {
        if ($this->encoder === null) {
            $this->encoder = new JsonEncoder(new JsonValidator());
        }
    }

    private int $jsonSerializeFlags = 0;

    public function serialize(Contracts\JsonSerializable $object, JsonFormat $format): string
    {
        $classObject = new stdClass();
        $reflectionScope = new ReflectionObject($object);

        $this->serializeProperties($reflectionScope, $object, $classObject);

        if (JsonFormat::Pretty === $format) {
            $this->jsonSerializeFlags |= JSON_PRETTY_PRINT;
        }

//        $encoder = new JsonEncoder(new JsonValidator(), flags: $this->jsonSerializeFlags);

        return $this->encoder->encode($classObject)->getBody();
    }

    private function serializeProperties(
        ReflectionObject $reflectionScope,
        Contracts\JsonSerializable $object,
        stdClass $classObject,
    ): void {
    }
}
