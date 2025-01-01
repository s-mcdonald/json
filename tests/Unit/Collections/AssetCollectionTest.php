<?php

declare(strict_types=1);

namespace Signal\Core\Tests\Unit\Collections;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Signal\Core\Collections\AssetCollection;
use Signal\Core\ValueObjects\AssetSymbol;
use Signal\Core\ValueObjects\AssetVo;

class AssetCollectionTest extends TestCase
{
    /**
     * Although all items are of same type, they must be AssetInterface
     */
    public function testConstructorThrowsExceptionOnIncorrectDefaultType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('All items must be of type: Signal\Core\Asset\Contracts\AssetInterface');

        new AssetCollection([1, 2, 3]);
    }

    public function testConstructorSetsTypeCorrectly(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetVo(new AssetSymbol("bar"));
        $collection = new AssetCollection([
            $first,
            $second,
            new AssetVo(new AssetSymbol("baz")),
        ]);

        $this->assertStringStartsWith('{Signal\Core\Collections\AssetCollection}=', $collection->__toString());
        $this->assertEquals(0, $collection->indexOf($first));
        $this->assertEquals(1, $collection->indexOf($second));
        $this->assertEquals(1, $collection->indexOf($second));
        $this->assertFalse($collection->isEmpty());
        $this->assertEquals(3, $collection->count());
    }

    public function testConstructorThrowsExceptionWithMixedTypes(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetSymbol("bar");

        $this->expectException(InvalidArgumentException::class);
        new AssetCollection([$first, $second]);
    }

    public function testAddItems(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetVo(new AssetSymbol("bar"));

        $collection = new AssetCollection([$first]);
        $collection->add($second);
        $this->assertTrue($collection->contains($first));
        $this->assertSame(2, $collection->size());
    }

    public function testRemoveItems(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetVo(new AssetSymbol("bar"));

        $collection = new AssetCollection([$first, $second]);
        $collection->remove($second);
        $this->assertTrue($collection->contains($first));
        $this->assertFalse($collection->contains($second));
    }

    public function testToArray(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetVo(new AssetSymbol("bar"));

        $collection = new AssetCollection([$first, $second]);
        $this->assertEquals([$first, $second], $collection->toArray());
    }

    public function testIsEmpty(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));

        $collection = new AssetCollection();
        $this->assertTrue($collection->isEmpty());

        $collection->add($first);
        $this->assertFalse($collection->isEmpty());
    }

    public function testContains(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetVo(new AssetSymbol("bar"));

        $collection = new AssetCollection([$first]);
        $this->assertTrue($collection->contains($first));
        $this->assertFalse($collection->contains($second));
    }

    public function testSize(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetVo(new AssetSymbol("bar"));

        $collection = new AssetCollection([$first]);
        $this->assertSame(1, $collection->size());
    }

    public function testFilter(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetVo(new AssetSymbol("bar"));
        $third = new AssetVo(new AssetSymbol("baz"));

        $collection = new AssetCollection([$first, $second, $third,]);
        $filtered = $collection->filter(static fn ($item) => $item->getSymbol()->getValue() === "foo");
        $this->assertSame([$first], $filtered->toArray());
    }

    public function testMap(): void
    {
        $this->markTestIncomplete('WIP');
    }

    public function testFirst(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetVo(new AssetSymbol("bar"));
        $third = new AssetVo(new AssetSymbol("baz"));

        $collection = new AssetCollection([$first, $second, $third]);

        $this->assertSame($first, $collection->first());

//        $filtered = $collection->first(static fn ($item) => $item->getSymbol()->getValue() !== "foo");
//        $this->assertSame([$second, $third], $filtered);
    }

    public function testClear(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetVo(new AssetSymbol("bar"));
        $third = new AssetVo(new AssetSymbol("baz"));

        $collection = new AssetCollection([$first, $second, $third]);
        $collection->clear();
        $this->assertTrue($collection->isEmpty());
    }

    public function testIndexOf(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetVo(new AssetSymbol("bar"));
        $third = new AssetVo(new AssetSymbol("baz"));
        $fourth = new AssetVo(new AssetSymbol("quz"));

        $collection = new AssetCollection([$first, $second, $third]);
        $this->assertSame(1, $collection->indexOf($second));
        $this->assertFalse($collection->indexOf($fourth));
    }

    public function testExistsBy(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetVo(new AssetSymbol("bar"));
        $third = new AssetVo(new AssetSymbol("baz"));

        $collection = new AssetCollection([$first, $second, $third]);

        $this->assertTrue($collection->existsBy(fn ($key, $item) => $item->getSymbol()->getValue() !== "foo"));
        $this->assertFalse($collection->existsBy(fn ($key, $item) => $item->getSymbol()->getValue() === "baaaar"));
    }

    public function testAddAll(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $collection1 = new AssetCollection([$first,]);

        $second = new AssetVo(new AssetSymbol("bar"));
        $third = new AssetVo(new AssetSymbol("baz"));

        $collection2 = new AssetCollection([$second, $third]);

        $collection1->addAll($collection2);

        $this->assertSame([$first, $second, $third], $collection1->toArray());
    }

    public function testAddAllThrowsExceptionForDifferentType(): void
    {
        $this->markTestIncomplete('WIP');
    }

    public function testAll(): void
    {
        $this->markTestIncomplete('WIP');
    }

    public function testGetIterator(): void
    {
        $this->markTestIncomplete('WIP');
    }

    public function testToString(): void
    {
        $first = new AssetVo(new AssetSymbol("foo"));
        $second = new AssetVo(new AssetSymbol("bar"));
        $collection = new AssetCollection([
            $first,
            $second,
            new AssetVo(new AssetSymbol("baz")),
        ]);

        $this->assertStringStartsWith('{Signal\Core\Collections\AssetCollection}=', $collection->__toString());
    }
}
