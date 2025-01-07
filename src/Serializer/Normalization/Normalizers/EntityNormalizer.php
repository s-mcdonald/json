<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalization\Normalizers;

use ReflectionProperty;
use SamMcDonald\Json\Builder\JsonBuilder;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use SamMcDonald\Json\Serializer\Normalization\Normalizers\Context\Context;
use SamMcDonald\Json\Serializer\Normalization\Normalizers\Context\ContextBuilder;
use SamMcDonald\Json\Serializer\Normalization\Normalizers\Contracts\AbstractClassNormalizer;

/**
 * Normalize object properties to stdClass. Similar to ObjectNormalizer however
 * this simply takes all properties of an object and does
 * not deal with Attributes or methods.
 */
final class EntityNormalizer extends AbstractClassNormalizer
{
    protected function transferToJsonBuilder(object $propertyValue): JsonBuilder
    {
        $jsonBuilder = new JsonBuilder();
        $contextBuilder = new ContextBuilder($this->propertyReader);

        foreach ($this->getReflectionProperties($propertyValue) as $prop) {
            assert($prop instanceof ReflectionProperty);
            $this->processProperty($contextBuilder->build($prop, $propertyValue, $jsonBuilder));
        }

        return $jsonBuilder;
    }

    protected function mapArrayContents(array $array): array
    {
        $newArray = [];
        foreach ($array as $value) {
            $newArray[] = match (true) {
                is_null($value) => null,
                is_bool($value), is_scalar($value) => $value,
                is_array($value) => $this->mapArrayContents($value),
                is_object($value) => $this->transferToJsonBuilder($value),
                default => throw new JsonSerializableException('Invalid type in array.'),
            };
        }

        return $newArray;
    }

    private function processProperty(Context $context): void
    {
        if (false === $context->getReflectionItem()->isInitialized($context->getOriginalObject())) {
            return;
        }

        $propertyValue = $this->getValueFromPropOrMethod($context->getReflectionItem(), $context->getOriginalObject());

        if (false === $this->canValueSerializable($propertyValue)) {
            return;
        }

        $this->assignToStdClass($context->getPropertyName(), $propertyValue, $context->getClassObject());
    }
}
