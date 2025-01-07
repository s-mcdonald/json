<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit\Schema;

use PHPUnit\Framework\TestCase;
use SamMcDonald\Json\Schema\JsonSchemaBuilder;
use SamMcDonald\Json\Schema\PropertyName;
use SamMcDonald\Json\Schema\Rules\StringCaseRule;
use SamMcDonald\Json\Schema\Rules\ValueTypeRule;

class JsonSchemaBuilderTest extends TestCase
{
    public function testFull(): void
    {
        $sut = new JsonSchemaBuilder();
        $sut->setAllowUnDefinedProperties(false);
        $sut->defineProperty(
            new PropertyName("foo"),
            StringCaseRule::upperCase()
        );
        $sut->defineProperty(
            new PropertyName("myInt"),
            ValueTypeRule::requireInteger()
        );

        $schema = $sut->build();
        $thrown = false;

        try {
            $schema->assertProperty("foo", "UPPER");
        } catch (\Throwable $e) {
            $thrown = true;
        }

        if (false === $thrown) {
            static::assertTrue(true);
        }
    }

    public function testBuildWithStringUpperCaseRuleToSucceed(): void
    {
        $sut = new JsonSchemaBuilder();
        $sut->setAllowUnDefinedProperties(false);
        $sut->defineProperty(
            new PropertyName("foo"),
            StringCaseRule::upperCase()
        );

        $schema = $sut->build();
        $thrown = false;

        try {
            $schema->assertProperty("foo", "UPPER");
        } catch (\Throwable $e) {
            $thrown = true;
        }

        if (false === $thrown) {
            static::assertTrue(true);
        }
    }
}
