<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding\Components;

use Exception;
use SamMcDonald\Json\Serializer\Encoding\Contracts;
use SamMcDonald\Json\Serializer\Encoding\Contracts\EncodingResultInterface;
use SamMcDonald\Json\Serializer\Encoding\JsonEncodingResult;

readonly class JsonToArrayDecoder implements Contracts\DecoderInterface
{
    public function __construct(
        private int $depth = 512,
    ) {
    }

    public function decode(string $jsonValue, string|null $fqClassName = null): EncodingResultInterface
    {
        try {
            $decodedData = json_decode($jsonValue, true, $this->depth, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            return new JsonEncodingResult(
                '',
                $e->getMessage(),
                false,
            );
        }

        return new JsonEncodingResult(
            $decodedData,
            $fqClassName ?? '',
            true,
        );
    }
}
