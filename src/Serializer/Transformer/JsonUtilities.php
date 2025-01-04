<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Transformer;

use Exception;
use SamMcDonald\Json\Serializer\Exceptions\JsonException;
use SamMcDonald\Json\Serializer\Formatter\JsonFormatter;

class JsonUtilities
{
    public function prettify(string $json): string
    {
        return (new JsonFormatter())->pretty($json);
    }

    public function uglify(string $json): string
    {
        return (new JsonFormatter())->ugly($json);
    }

    public function isValid(string $json): bool
    {
        try {
            json_decode($json, true, 512, flags: JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    public function push(string $json, mixed $item): string|false
    {
        if (false === $this->isValid($json)) {
            return false;
        }

        try {
            $decodedData = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            $decodedData[] = $item;

            return json_encode($decodedData, flags: JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            throw new JsonException('Invalid JSON:' . $e->getMessage());
        }
    }

    public function remove(string $json, string $property): string|false
    {
        if (false === $this->isValid($json)) {
            return false;
        }

        try {
            $decodedData = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            unset($decodedData[$property]);

            return json_encode($decodedData, flags: JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            throw new JsonException('Invalid JSON:' . $e->getMessage());
        }
    }

    public function toArray(string $json): array|false
    {
        if (false === $this->isValid($json)) {
            return false;
        }

        try {
            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            throw new JsonException('Invalid JSON:' . $e->getMessage());
        }
    }
}
