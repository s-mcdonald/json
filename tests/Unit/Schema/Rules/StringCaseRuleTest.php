<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Schema\Rules;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Schema\Rules\StringCaseRule;

class StringCaseRuleTest extends TestCase
{
    public function testCreateDefaultToNull(): void
    {
        $sut = StringCaseRule::create();
        try {
            $sut->check('foo');
            $sut->check('FOO');
        } catch (\Throwable $e) {
            $this->fail($e->getMessage());
        }
        $this->assertTrue(true);
    }

    public function testCreateUpper(): void
    {
        $sut = StringCaseRule::upperCase();
        try {
            $sut->check('FOO');
        } catch (\Throwable $e) {
            $this->fail($e->getMessage());
        }
        $this->assertTrue(true);
    }

    public function testCreateUpperThrowsWhenLowerCaseFound(): void
    {
        $thrown = false;
        $sut = StringCaseRule::upperCase();
        try {
            $sut->check('foo');
        } catch (\Throwable $e) {
            $thrown = true;
        }

        if ($thrown) {
            $this->assertTrue(true);
        }
    }

    public function testCreateLower(): void
    {
        $sut = StringCaseRule::lowerCase();
        try {
            $sut->check('foo');
        } catch (\Throwable $e) {
            $this->fail($e->getMessage());
        }
        $this->assertTrue(true);
    }

    public function testCreateLowerThrowsWhenUpper(): void
    {
        $thrown = false;
        $sut = StringCaseRule::lowerCase();

        try {
            $sut->check('fooUpp');
        } catch (\Throwable $e) {
            $thrown = true;
        }

        if ($thrown) {
            $this->assertTrue(true);
        }
    }
}
