<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Hydration;

use PHPUnit\Framework\Attributes\CoversClass;
use SamMcDonald\Json\Serializer\Hydration\HydrationConfiguration;
use PHPUnit\Framework\TestCase;

#[CoversClass(HydrationConfiguration::class)]
class HydrationConfigurationTest extends TestCase
{
    public function testConfig(): void
    {
        $sut = new HydrationConfiguration();
        $sut->propertyHydrationTypeStrictMode = true;

        static::assertTrue($sut->propertyHydrationTypeStrictMode);
    }

    public function testConfigStrictDisabled(): void
    {
        $sut = new HydrationConfiguration();
        $sut->propertyHydrationTypeStrictMode = false;

        static::assertFalse($sut->propertyHydrationTypeStrictMode);
    }
}
