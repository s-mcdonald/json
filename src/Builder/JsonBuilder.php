<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Builder;

use JsonException;
use SamMcDonald\Json\Serializer\Contracts\JsonSerializable;
use stdClass;

final class JsonBuilder implements JsonSerializable
{
    private array $jsonProperties = [];

    /**
     * @throws JsonException
     */
    public function __toString(): string
    {
        return json_encode($this->jsonProperties, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }

    public function addObjectProperty(string $prop, self $value): self
    {
        return $this->addProperty($prop, $value->getArray());
    }

    public function toStdClass(): stdClass
    {
        try {
            return json_decode((string) $this, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return new stdClass();
        }
    }

    public function addArrayProperty(string $prop, array $value): self
    {
        // array value can only be an array containing int|string|null|false|true|float|object
        foreach ($value as $item) {
            if (!is_int($item) && !is_string($item) && !is_null($item)
                && !is_bool($item) && !is_float($item) && !is_object($item)) {
                trigger_error(
                    'Invalid json array value',
                );
            }
        }

        return $this->addProperty($prop, $value);
    }

    public function addStringProperty(string $prop, string $value): self
    {
        return $this->addProperty($prop, $value);
    }

    public function addBooleanProperty(string $prop, bool $value): self
    {
        return $this->addProperty($prop, $value);
    }

    public function addNumericProperty(string $prop, int|float $value): self
    {
        return $this->addProperty($prop, $value);
    }

    public function addNullProperty(string $prop): self
    {
        return $this->addProperty($prop);
    }

    public function build(): string
    {
        return (string) $this;
    }

    protected function addProperty(string $prop, $value = null): self
    {
        $this->validatePropertyName($prop);
        $this->jsonProperties[$prop] = $value;

        return $this;
    }

    protected function validatePropertyName(string $prop): bool
    {
        if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9-_]*$/', $prop)) {
            trigger_error(
                'Invalid json property name',
            );

            return false;
        }

        return true;
    }

    private function getArray(): array
    {
        return $this->jsonProperties;
    }
}
