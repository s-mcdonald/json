<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Serializer\Encoding\Components\Flags;

use SamMcDonald\Json\Serializer\Encoding\Components\Flags\EncodeFlags;
use PHPUnit\Framework\TestCase;

class EncodeFlagsTest extends TestCase
{
    public function testInitializeEncodeFlags(): void
    {
        $flags = EncodeFlags::create();
        $this->assertInstanceOf(EncodeFlags::class, $flags);
        $this->assertSame(0, $flags->getFlags());
    }

    public function testWithUnescapeSlashes(): void
    {
        $flags = EncodeFlags::create();

        $this->assertFalse($flags->hasUnescapeSlashes());

        $modifiedFlags = $flags->withUnescapeSlashes(true);
        $this->assertTrue($modifiedFlags->hasUnescapeSlashes());
        $this->assertNotSame($flags, $modifiedFlags);

        $resetFlags = $modifiedFlags->withUnescapeSlashes(false);
        $this->assertFalse($resetFlags->hasUnescapeSlashes());
        $this->assertNotSame($modifiedFlags, $resetFlags);
    }

    public function testWithUnescapeUnicode(): void
    {
        $flags = EncodeFlags::create();

        $this->assertFalse($flags->hasUnescapeUnicode());

        $modifiedFlags = $flags->withUnescapeUnicode(true);
        $this->assertTrue($modifiedFlags->hasUnescapeUnicode());
        $this->assertNotSame($flags, $modifiedFlags);

        $resetFlags = $modifiedFlags->withUnescapeUnicode(false);
        $this->assertFalse($resetFlags->hasUnescapeUnicode());
        $this->assertNotSame($modifiedFlags, $resetFlags);
    }

    public function testWithHexQuoteTags(): void
    {
        $flags = EncodeFlags::create();

        $this->assertFalse($flags->hasHexQuoteTags());

        $modifiedFlags = $flags->withHexQuoteTags(true);
        $this->assertTrue($modifiedFlags->hasHexQuoteTags());
        $this->assertNotSame($flags, $modifiedFlags);

        $resetFlags = $modifiedFlags->withHexQuoteTags(false);
        $this->assertFalse($resetFlags->hasHexQuoteTags());
        $this->assertNotSame($modifiedFlags, $resetFlags);
    }

    public function testWithHexAposTags(): void
    {
        $flags = EncodeFlags::create();

        $this->assertFalse($flags->hasHexAposTags());

        $modifiedFlags = $flags->withHexAposTags(true);
        $this->assertTrue($modifiedFlags->hasHexAposTags());
        $this->assertNotSame($flags, $modifiedFlags);

        $resetFlags = $modifiedFlags->withHexAposTags(false);
        $this->assertFalse($resetFlags->hasHexAposTags());
        $this->assertNotSame($modifiedFlags, $resetFlags);
    }

    public function testWithHexAmpTags(): void
    {
        $flags = EncodeFlags::create();

        $this->assertFalse($flags->hasHexAmpTags());

        $modifiedFlags = $flags->withHexAmpTags(true);
        $this->assertTrue($modifiedFlags->hasHexAmpTags());
        $this->assertNotSame($flags, $modifiedFlags);

        $resetFlags = $modifiedFlags->withHexAmpTags(false);
        $this->assertFalse($resetFlags->hasHexAmpTags());
        $this->assertNotSame($modifiedFlags, $resetFlags);
    }

    public function testWithHexTags(): void
    {
        $flags = EncodeFlags::create();

        $this->assertFalse($flags->hasHexTags());

        $modifiedFlags = $flags->withHexTags(true);
        $this->assertTrue($modifiedFlags->hasHexTags());
        $this->assertNotSame($flags, $modifiedFlags);

        $resetFlags = $modifiedFlags->withHexTags(false);
        $this->assertFalse($resetFlags->hasHexTags());
        $this->assertNotSame($modifiedFlags, $resetFlags);
    }
}
