<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer;

use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;
use SamMcDonald\Json\Serializer\Encoding\Contracts\EncoderInterface;
use SamMcDonald\Json\Serializer\Encoding\JsonEncoder;
use SamMcDonald\Json\Serializer\Encoding\Validator\JsonValidator;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;
use SamMcDonald\Json\Serializer\Normalizers\ObjectNormalizer;

class JsonSerializer
{
    public function __construct(
        private EncoderInterface|null $encoder = null,
        private ObjectNormalizer|null $objectNormalizer = null,
    ) {
        if (null === $this->encoder) {
            $this->encoder = new JsonEncoder(new JsonValidator());
        }

        if (null === $this->objectNormalizer) {
            $this->objectNormalizer = new ObjectNormalizer(new JsonPropertyReader());
        }
    }

    public function serialize(JsonSerializable $object, JsonFormat $format): string
    {
        return $this->encoder->encode($this->objectNormalizer->serializeJsonSerializableToStdObject($object), $format)->getBody();
    }
}
