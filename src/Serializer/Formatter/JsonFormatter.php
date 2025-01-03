<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Formatter;

final class JsonFormatter
{
    public function pretty(string $json): string
    {
        return $this->reEncode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function ugly(string $json): string
    {
        return $this->reEncode($json, JSON_UNESCAPED_SLASHES);
    }

    private function reEncode(string $json, int $options): string
    {
        $decoded = json_decode($json, true, 512);
        if (JSON_ERROR_NONE !== json_last_error()) {
            return $json;
        }
        $encoded = json_encode($decoded, $options);
        if (false === $encoded) {
            return $json;
        }

        return $encoded;
    }
}
