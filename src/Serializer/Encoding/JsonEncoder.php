<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding;

use Exception;
use SamMcDonald\Json\Serializer\Encoding\Contracts\EncoderInterface;
use SamMcDonald\Json\Serializer\Encoding\Contracts\EncodingResultInterface;
use SamMcDonald\Json\Serializer\Encoding\Validator\Contracts\JsonValidatorInterface;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;

readonly class JsonEncoder implements EncoderInterface
{
    /**
     * $depth - this should also be on the encode, perhaps we introduce a config?
     */
    public function __construct(
        private JsonValidatorInterface $validator,
        private int $depth = 512,
    ) {
    }

    public function encode($value, JsonFormat $format = JsonFormat::Pretty): EncodingResultInterface
    {
        $flags = 0;

        if (false === $this->validator->validate($value)) {
            return new JsonEncodingResult($this->validator->getLastErrorMessage());
        }

        if (JsonFormat::Pretty === $format) {
            $flags |= JSON_PRETTY_PRINT;
        }

        try {
            $encoded = json_encode($value, JSON_THROW_ON_ERROR | $flags, $this->depth);
        } catch (Exception $e) {
            return new JsonEncodingResult($this->validator->getLastErrorMessage());
        }

        if (false === $encoded) {
            return new JsonEncodingResult('Unknown decode exception');
        }

        return new JsonEncodingResult($encoded, isValid: true);
    }
}
