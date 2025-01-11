<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Encoding\Components\Flags;

use PHPUnit\Framework\Attributes\CoversClass;
use SamMcDonald\Json\Serializer\Encoding\Components\Flags\AbstractFlags;
use SamMcDonald\Json\Serializer\Encoding\Components\Flags\DecodeFlags;
use PHPUnit\Framework\TestCase;

#[CoversClass(DecodeFlags::class)]
#[CoversClass(AbstractFlags::class)]
class DecodeFlagsTest extends TestCase
{
    public function testInitializeDecodeFlags(): void
    {
        $flags = DecodeFlags::create();
        $this->assertInstanceOf(DecodeFlags::class, $flags);
        $this->assertSame(0, $flags->getFlags());
    }

    public function testWithBigIntAsString(): void
    {
        $flags = DecodeFlags::create();

        // Ensure it's initially not set
        $this->assertFalse($flags->hasBigIntAsString());

        // Set the flag and check
        $modifiedFlags = $flags->withBigIntAsString(true);
        $this->assertTrue($modifiedFlags->hasBigIntAsString());
        $this->assertNotSame($flags, $modifiedFlags);

        // Unset the flag and check
        $resetFlags = $modifiedFlags->withBigIntAsString(false);
        $this->assertFalse($resetFlags->hasBigIntAsString());
        $this->assertNotSame($modifiedFlags, $resetFlags);
    }

    public function testWithIgnoreInvalidUtf8(): void
    {
        $flags = DecodeFlags::create();

        // Ensure it's initially not set
        $this->assertFalse($flags->hasIgnoreInvalidUtf8());

        // Set the flag and check
        $modifiedFlags = $flags->withIgnoreInvalidUtf8(true);
        $this->assertTrue($modifiedFlags->hasIgnoreInvalidUtf8());
        $this->assertNotSame($flags, $modifiedFlags);

        $resetFlags = $modifiedFlags->withIgnoreInvalidUtf8(false);
        $this->assertFalse($resetFlags->hasIgnoreInvalidUtf8());
        $this->assertNotSame($modifiedFlags, $resetFlags);
    }
}
