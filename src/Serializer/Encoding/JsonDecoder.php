<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding;

use Exception;
use SamMcDonald\Json\Serializer\Encoding\Contracts\EncodingResultInterface;
use SamMcDonald\Json\Serializer\Hydrator;

readonly class JsonDecoder implements Contracts\DecoderInterface
{
    public function __construct(
        private Hydrator|null $hydrator = null,
        private int $depth = 512,
    ) {
    }

    public function decode(string $jsonValue, string|null $fqClassName = null): EncodingResultInterface
    {
        try {
            $decodedData = json_decode($jsonValue, false, $this->depth, JSON_THROW_ON_ERROR);

            if (null !== $fqClassName && null !== $this->hydrator) {
                $decodedData = $this->hydrator->hydrate($decodedData, $fqClassName);
            }
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
