<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Builder;

final class JsonBuilder extends AbstractJsonBuilder
{
    public function addNullProperty(string $prop): self
    {
        self::assertPropertyName($prop);

        return $this->addProperty($prop, null);
    }
}
