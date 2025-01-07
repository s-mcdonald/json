<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalization\Normalizers;

use ReflectionAttribute;
use ReflectionMethod;
use ReflectionProperty;
use SamMcDonald\Json\Builder\JsonBuilder;
use SamMcDonald\Json\Serializer\Attributes\JsonProperty;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use SamMcDonald\Json\Serializer\Normalization\Normalizers\Context\Context;
use SamMcDonald\Json\Serializer\Normalization\Normalizers\Context\ContextBuilder;
use SamMcDonald\Json\Serializer\Normalization\Normalizers\Contracts\AbstractClassNormalizer;

/**
 * Normalize from object to stdClass. This includes Serializable methods with the
 * use of Attributes.
 */
final class ObjectNormalizer extends AbstractClassNormalizer
{
    protected function transferToJsonBuilder(object $propertyValue): JsonBuilder
    {
        $jsonBuilder = new JsonBuilder();
        $contextBuilder = new ContextBuilder($this->propertyReader);

        foreach ($this->getReflectionProperties($propertyValue) as $prop) {
            assert($prop instanceof ReflectionProperty);
            $this->processProperty($contextBuilder->build($prop, $propertyValue, $jsonBuilder));
        }

        foreach ($this->getReflectionMethods($propertyValue) as $method) {
            assert($method instanceof ReflectionMethod);
            $this->processMethod($contextBuilder->build($method, $propertyValue, $jsonBuilder));
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
        if (0 === count($context->getJsonPropertyAttributes())) {
            return;
        }

        if (count($context->getJsonPropertyAttributes()) > 1) {
            throw new JsonSerializableException(
                sprintf(
                    'Must have only 1 %s Attribute.',
                    JsonProperty::class,
                ),
            );
        }

        if (false === $context->getReflectionItem()->isInitialized($context->getOriginalObject())) {
            throw new JsonSerializableException(
                sprintf(
                    'Value not initialized: %s',
                    $context->getPropertyName(),
                ),
            );
        }

        $propertyValue = $this->getValueFromPropOrMethod($context->getReflectionItem(), $context->getOriginalObject());

        if (false === $this->isSerializable($propertyValue, $context->getJsonPropertyAttributes())) {
            return;
        }

        $this->assignToStdClass($context->getPropertyName(), $propertyValue, $context->getClassObject());
    }

    private function processMethod(Context $context): void
    {
        if (0 === count($context->getJsonPropertyAttributes())) {
            return;
        }

        if (count($context->getJsonPropertyAttributes()) > 1) {
            throw new JsonSerializableException('Must have only 1 JsonProperty Attribute.');
        }

        $propertyValue = $this->getValueFromPropOrMethod($context->getReflectionItem(), $context->getOriginalObject());

        if (false === $this->isSerializable($propertyValue, $context->getJsonPropertyAttributes())) {
            return;
        }

        if ($context->getReflectionItem()->getNumberOfRequiredParameters() > 0) {
            throw new JsonSerializableException(
                'Can not associate JsonProperty on a function with required parameters.',
            );
        }

        $this->assignToStdClass($context->getPropertyName(), $propertyValue, $context->getClassObject());
    }

    /**
     * @param array<ReflectionAttribute> $attributes
     */
    private function isSerializable($propertyValue, array $attributes): bool
    {
        if (false === $this->propertyReader->hasJsonPropertyAttributes($attributes)) {
            return false;
        }

        if ($this->canValueSerializable($propertyValue)) {
            return true;
        }

        return false;
    }
}
