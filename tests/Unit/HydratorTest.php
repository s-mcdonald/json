<?php

declare(strict_types=1);

namespace SamMcDonald\Json\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use SamMcDonald\Json\Serializer\Hydration\Exceptions\HydrationException;
use SamMcDonald\Json\Serializer\Hydrator;
use SamMcDonald\Json\Tests\Fixtures\Entities\BadClass\VeryBadClass;
use SamMcDonald\Json\Tests\Fixtures\Entities\ClassWithPublicStringProperty;
use SamMcDonald\Json\Tests\Fixtures\Entities\ContainsSetters\UserWithSetters;
use SamMcDonald\Json\Tests\Fixtures\Entities\SimplePropertiesNoOverrideClass;

#[CoversClass(Hydrator::class)]
class HydratorTest extends TestCase
{
    public function testHydrationWithTypeDifferenceWithNonStrict(): void
    {
        $this->markTestSkipped('Feature not yet implemented.');

        $input = ["name" => "foo-name", "age" =>  '44' ];
        $expected = new SimplePropertiesNoOverrideClass('foo-name', 44);

        $sut = new Hydrator();
        $hydrated = $sut->hydrate($input, SimplePropertiesNoOverrideClass::class);

        static::assertEquals(
            $expected,
            $hydrated,
        );
    }

    public function testHydrationFailureWithTypeDifference(): void
    {
        $this->expectException(HydrationException::class);
        $this->expectExceptionMessage('Unable to parse hydration data: Expected array but got integer');

        $input = ["name" => "foo-name", "age" => ["keyname" => 44] ];

        $sut = new Hydrator();

        $sut->hydrate($input, SimplePropertiesNoOverrideClass::class);
    }

    public function testHydrationFailureUsingBadArrayKeyForPropertyName(): void
    {
        $this->expectException(HydrationException::class);

        $input = ["name" => "foo-name", ["age" => 44] ];

        $sut = new Hydrator();

        $sut->hydrate($input, SimplePropertiesNoOverrideClass::class);
    }

    public function testHydrationBasic(): void
    {
        $expected = new SimplePropertiesNoOverrideClass('foo-name', 44);

        $input = ["name" => "foo-name", "age" => 44 ];

        $sut = new Hydrator();

        $hydrated = $sut->hydrate($input, SimplePropertiesNoOverrideClass::class);
        assert($hydrated instanceof SimplePropertiesNoOverrideClass);

        static::assertEquals(
            $expected,
            $hydrated,
        );

        static::assertEquals(
            'foo-name',
            $hydrated->getName(),
        );

        static::assertEquals(
            44,
            $hydrated->getAge(),
        );
    }

    public function testHydrationToSetterMethod(): void
    {
        $expected = new UserWithSetters('foo-name', 44);

        $input = ["userName" => "foo-name", "age" => 44 ];

        $sut = new Hydrator();

        $hydrated = $sut->hydrate($input, UserWithSetters::class);
        assert($hydrated instanceof UserWithSetters);

        static::assertEquals(
            $expected,
            $hydrated,
        );

        static::assertEquals(
            'foo-name',
            $hydrated->getName(),
        );

        static::assertEquals(
            44,
            $hydrated->getAge(),
        );
    }

    public function testHydrateWhenClassNotExist(): void
    {
        $this->expectException(HydrationException::class);
        $this->expectExceptionMessage('Unable to hydrate data: Class Foo\Bar\Baz\Classy does not exist');

        $sut = new Hydrator();
        $sut->hydrate(["foo" => "bar"],'Foo\Bar\Baz\Classy');
    }

    /**
     * Foo does not exist in the class
     */
    public function testHydrateWhenPropertyDoesntExit(): void
    {
        $sut = new Hydrator();
        $o = $sut->hydrate(["foo" => "bar"],ClassWithPublicStringProperty::class);

        static::assertFalse(
            isset($o->name),
        );

        static::assertFalse(
            isset($o->foo)
        );
    }
}
