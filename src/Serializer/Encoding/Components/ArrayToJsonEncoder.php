<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding\Components;

use Exception;
use SamMcDonald\Json\Serializer\Encoding\Contracts\ArrayEncoderInterface;
use SamMcDonald\Json\Serializer\Encoding\Contracts\EncodingResultInterface;
use SamMcDonald\Json\Serializer\Encoding\JsonEncodingResult;
use SamMcDonald\Json\Serializer\Encoding\Validator\ValidationMessage;
use SamMcDonald\Json\Serializer\Enums\JsonFormat;

readonly class ArrayToJsonEncoder implements ArrayEncoderInterface
{
    public function __construct(
        private int $depth = 512,
    ) {
    }

    public function encode(array $value, JsonFormat $format = JsonFormat::Pretty): EncodingResultInterface
    {
        $flags = JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING;

        if (JsonFormat::Pretty === $format) {
            $flags |= JSON_PRETTY_PRINT;
        }

        try {
            $encoded = json_encode($value, $flags, $this->depth);
        } catch (Exception $e) {
            return new JsonEncodingResult($e->getMessage());
        }

        if (false === $encoded) {
            return new JsonEncodingResult(ValidationMessage::UNKNOWN_EXCEPTION);
        }

        return new JsonEncodingResult($encoded, isValid: true);
    }
}
