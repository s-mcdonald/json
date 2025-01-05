<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Builder;

use Exception;
use InvalidArgumentException;
use SamMcDonald\Json\Serializer\Encoding\Components\JsonToStdClassDecoder;
use SamMcDonald\Json\Serializer\Exceptions\JsonException;
use stdClass;

abstract class AbstractJsonBuilder
{
    private array $jsonProperties = [];

    public function __toString(): string
    {
        try {
            return json_encode($this->jsonProperties, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            throw new JsonException($e->getMessage());
        }
    }

    public function addProperty(string $prop, mixed $value): self
    {
        self::assertPropertyName($prop);

        return $this->addProp($prop, self::cleanValue($value));
    }

    public function toStdClass(): stdClass
    {
        try {
            return (new JsonToStdClassDecoder())->decode((string) $this)->getBody();
        } catch (Exception $e) {
            throw new JsonException($e->getMessage());
        }
    }

    public function toArray(): array
    {
        try {
            return json_decode((string) $this, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            throw new JsonException($e->getMessage());
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

    private function addProp(string $prop, mixed $value = null): self
    {
        $this->jsonProperties[$prop] = $value;

        return $this;
    }
}
