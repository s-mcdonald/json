<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Builder;

use SamMcDonald\Json\Json;
use SamMcDonald\Json\Serializer\Exceptions\JsonException;

final class JsonBuilder extends AbstractJsonBuilder
{
    public function addNullProperty(string $prop): self
    {
        self::assertPropertyName($prop);

        return $this->addProperty($prop, null);
    }

    public static function createFromJson(string $json): self
    {
        if (false === Json::isValid($json)) {
            throw new JsonException('Invalid source Json');
        }

        $builder = new self();
        foreach (Json::toArray($json) as $prop => $value) {
            $builder->addProperty($prop, $value);
        }

        return $builder;
    }
}
