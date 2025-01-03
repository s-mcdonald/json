<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding;

use Exception;
use SamMcDonald\Json\Serializer\Encoding\Contracts\DecodingResultInterface;
use SamMcDonald\Json\Serializer\Hydrator;

readonly class JsonDecoder implements Contracts\DecoderInterface
{
    public function __construct(
        private Hydrator $hydrator,
        private int $depth = 512,
    ) {
    }

    public function decode(string $jsonValue, string|null $fqClassName = null): DecodingResultInterface
    {
        try {
            $decodedData = json_decode($jsonValue, false, $this->depth, JSON_THROW_ON_ERROR);

            if (null !== $fqClassName) {
                $decodedData = $this->hydrator->hydrate($decodedData, $fqClassName);
            }
        } catch (Exception $e) {
            return new JsonDecodingResult(
                '',
                $e->getMessage(),
                false,
            );
        }

        return new JsonDecodingResult(
            $decodedData,
            $fqClassName ?? '',
            true,
        );
    }
}
