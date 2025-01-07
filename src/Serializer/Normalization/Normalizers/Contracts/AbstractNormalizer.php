<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalization\Normalizers\Contracts;

abstract class AbstractNormalizer implements NormalizerInterface
{
    protected function canValueSerializable($propertyValue): bool
    {
        return null === $propertyValue
            || is_scalar($propertyValue)
            || is_array($propertyValue)
            || is_bool($propertyValue)
            || is_object($propertyValue);
    }
}
