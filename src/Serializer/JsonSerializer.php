<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer;

use ReflectionObject;
use SamMcDonald\Json\Serializer\Encoding\JsonEncoder;
use SamMcDonald\Json\Serializer\Encoding\Validator\JsonValidator;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;
use stdClass;

class JsonSerializer
{
    private int $jsonSerializeFlags = 0;

    public function serialize(Contracts\JsonSerializable $object, JsonFormat $format): string
    {
        $classObject = new stdClass();
        $reflectionScope = new ReflectionObject($object);

        $this->serializeProperties($reflectionScope, $object, $classObject);

        if (JsonFormat::Pretty === $format) {
            $this->jsonSerializeFlags |= JSON_PRETTY_PRINT;
        }

        $encoder = new JsonEncoder(new JsonValidator(), flags: $this->jsonSerializeFlags);

        return $encoder->encode($classObject)->getBody();
    }

    private function serializeProperties(
        ReflectionObject $reflectionScope,
        Contracts\JsonSerializable $object,
        stdClass $classObject,
    ): void {
    }
}
