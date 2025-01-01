<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding;

use Exception;
use SamMcDonald\Json\Serializer\Encoding\Contracts\EncoderInterface;
use SamMcDonald\Json\Serializer\Encoding\Contracts\EncodingResultInterface;
use SamMcDonald\Json\Serializer\Encoding\Validator\Contracts\JsonValidatorInterface;

readonly class JsonEncoder implements EncoderInterface
{
    public function __construct(
        private JsonValidatorInterface $validator,
        private int $flags = 0,
        private int $depth = 512,
    ) {
    }

    public function encode($value): EncodingResultInterface
    {
        if (false === $this->validator->validate($value)) {
            return new JsonEncodingResult($this->validator->getLastErrorMessage());
        }

        try {
            $encoded = json_encode($value, JSON_THROW_ON_ERROR | $this->flags, $this->depth);
        } catch (Exception $e) {
            return new JsonEncodingResult($this->validator->getLastErrorMessage());
        }

        if (false === $encoded) {
            return new JsonEncodingResult('Unknown decode exception');
        }

        return new JsonEncodingResult($encoded, isValid: true);
    }
}
