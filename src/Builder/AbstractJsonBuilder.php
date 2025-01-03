<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Builder;

use InvalidArgumentException;
use JsonException;
use RuntimeException;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;
use stdClass;

abstract class AbstractJsonBuilder implements JsonSerializable
{
    private array $jsonProperties = [];

    /**
     * @throws JsonException
     */
    public function __toString(): string
    {
        return json_encode($this->jsonProperties, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }

    public function addProperty(string $prop, mixed $value): self
    {
        self::assertPropertyName($prop);

        return $this->addProp($prop, self::cleanValue($value));
    }

    public function toStdClass(): stdClass
    {
        try {
            return json_decode((string) $this, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    public function toArray(): array
    {
        try {
            return json_decode((string) $this, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    public function build(): string
    {
        return (string) $this;
    }

    protected function getArray(): array
    {
        return $this->jsonProperties;
    }

    private function addProp(string $prop, mixed $value = null): self
    {
        $this->jsonProperties[$prop] = $value;

        return $this;
    }

    protected function removeProp(string $prop): self
    {
        unset($this->jsonProperties[$prop]);

        return $this;
    }

    protected static function assertPropertyName(string $prop): void
    {
        if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9-_]*$/', $prop)) {
            throw new InvalidArgumentException('Invalid property name');
        }
    }

    protected static function cleanValue(mixed $value): mixed
    {
        if ($value instanceof self) {
            return $value->toStdClass();
        }

        if (is_array($value)) {
            $value = self::cleanArrayValue($value);
        }

        if (is_object($value)) {
            throw new InvalidArgumentException('Does not support Object types.');
        }

        return match (gettype($value)) {
            'array', 'string', 'double', 'integer', 'boolean', 'NULL' => $value,
            default => throw new InvalidArgumentException('Invalid value type - Received : ' . gettype($value)),
        };
    }

    protected static function cleanArrayValue(array $value): array
    {
        $returnArray = [];

        foreach ($value as $key => $val) {
            if (is_array($val)) {
                $returnArray[$key] = self::cleanArrayValue($val);
                continue;
            }

            $returnArray[$key] = self::cleanValue($val);
        }

        return $returnArray;
    }
}
