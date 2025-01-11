<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Encoding;

use Exception;
use SamMcDonald\Json\Serializer\Encoding\Components\Flags\DecodeFlags;
use SamMcDonald\Json\Serializer\Encoding\Components\Flags\EncodeOptions;
use SamMcDonald\Json\Serializer\Encoding\Contracts\EncodingResultInterface;
use SamMcDonald\Json\Serializer\Hydrator;

class JsonDecoder implements Contracts\DecoderInterface
{
    public function __construct(
        private Hydrator|null $hydrator = null,
        private EncodeOptions|null $options = null,
    ) {
        if (null === $this->options) {
            $this->options = new EncodeOptions(DecodeFlags::create(), 512);
        }
    }

    public function decode(string $jsonValue, string|null $fqClassName = null): EncodingResultInterface
    {
        $flags = $this->options->getFlagsValue();

        try {
            $decodedData = json_decode($jsonValue, false, $this->options->getDepth(), JSON_THROW_ON_ERROR | $flags);

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
