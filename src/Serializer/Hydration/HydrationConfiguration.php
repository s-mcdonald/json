<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Serializer\Hydration;

class HydrationConfiguration
{
    /**
     * When enabled, data types must strictly match without any casting.
     * Exceptions will be thrown if invalid types are provided.
     */
    public bool $propertyHydrationTypeStrictMode = true;
}
