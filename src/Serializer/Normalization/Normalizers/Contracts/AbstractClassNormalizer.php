<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Normalization\Normalizers\Contracts;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionEnum;
use ReflectionException;
use ReflectionMethod;
use ReflectionObject;
use ReflectionProperty;
use SamMcDonald\Json\Builder\JsonBuilder;
use SamMcDonald\Json\Serializer\Attributes\AttributeReader\JsonPropertyReader;
use SamMcDonald\Json\Serializer\Exceptions\JsonSerializableException;
use stdClass;
use TypeError;

abstract class AbstractClassNormalizer extends AbstractNormalizer
{
    public function __construct(
        protected readonly JsonPropertyReader $propertyReader,
    ) {
    }

    public function normalize(mixed $input): stdClass
    {
        if (false === is_object($input)) {
            throw new InvalidArgumentException('input must be an object.');
        }

        $reflectionClass = new ReflectionClass($input);
        if ($reflectionClass->isEnum()) {
            return $this->normalizeEnum($input);
        }

        return $this->transferToJsonBuilder($input)->toStdClass();
    }

    protected function getValueFromPropOrMethod(
        ReflectionMethod|ReflectionProperty $reflection,
        $originalObject,
    ): mixed {
        if (false === ($reflection instanceof ReflectionMethod)) {
            return $reflection->getValue($reflection->isStatic() ? null : $originalObject);
        }

        try {
            $propertyValue = $reflection->invoke($reflection->isStatic() ? null : $originalObject);
        } catch (TypeError $t) {
            throw new JsonSerializableException('Value has not been initialized.');
        } catch (ReflectionException $e) {
            throw new JsonSerializableException($e->getMessage());
        }

        return $propertyValue;
    }

    /**
     * @return array<ReflectionProperty>
     */
    protected function getReflectionProperties(object $originalObject): array
    {
        return (new ReflectionObject($originalObject))->getProperties(
            ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PRIVATE,
        );
    }

    /**
     * @return array<ReflectionMethod>
     */
    protected function getReflectionMethods(object $originalObject): array
    {
        return (new ReflectionObject($originalObject))->getMethods(
            ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PRIVATE,
        );
    }

    protected function normalizeEnum(object $propertyValue): stdClass
    {
        $reflectionClass = new ReflectionClass($propertyValue);

        $value = $propertyValue->name;
        if ((new ReflectionEnum($propertyValue))->isBacked()) {
            $value = $propertyValue->value;
        }

        $builder = new JsonBuilder();

        return $builder
            ->addProperty($reflectionClass->getShortName(), $value)
            ->toStdClass();
    }

    abstract protected function transferToJsonBuilder(object $propertyValue): JsonBuilder;

    protected function assignToStdClass($propertyName, $propertyValue, JsonBuilder $classObject): void
    {
        if (is_object($propertyValue)) {
            $classObject->addProperty($propertyName, $this->transferToJsonBuilder($propertyValue));

            return;
        }

        match (\gettype($propertyValue)) {
            'array' => $classObject->addProperty($propertyName, $this->mapArrayContents($propertyValue)),
            'NULL', 'boolean', 'string', 'integer', 'double' => $classObject->addProperty($propertyName, $propertyValue),
            default => throw new JsonSerializableException('Invalid type.'),
        };
    }
}
