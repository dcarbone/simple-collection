<?php

/**
 * Class SimpleCollectionTest
 */
class SimpleCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \DCarbone\SimpleCollection::__construct
     * @return \DCarbone\SimpleCollection
     */
    public function testCanConstructObjectWithNoArguments()
    {
        $collection = new \DCarbone\SimpleCollection();
        $this->assertInstanceOf('\\DCarbone\\SimpleCollection', $collection);
    }

    /**
     * @covers \DCarbone\SimpleCollection::__construct
     * @return \DCarbone\SimpleCollection
     */
    public function testCanConstructObjectWithArrayParameter()
    {
        $collection = new \DCarbone\SimpleCollection(array('key' => 'value'));
        $this->assertInstanceOf('\\DCarbone\\SimpleCollection', $collection);
    }

    /**
     * @depends testCanConstructObjectWithNoArguments
     */
    public function testObjectImplementsArrayAccess()
    {
        $collection = new \DCarbone\SimpleCollection();
        $this->assertInstanceOf('\\ArrayAccess', $collection);
    }

    /**
     * @depends testCanConstructObjectWithNoArguments
     */
    public function testObjectImplementsSeekableIterator()
    {
        $collection = new \DCarbone\SimpleCollection();
        $this->assertInstanceOf('\\SeekableIterator', $collection);
    }

    /**
     * @depends testCanConstructObjectWithNoArguments
     */
    public function testObjectImplementsCountable()
    {
        $collection = new \DCarbone\SimpleCollection();
        $this->assertInstanceOf('\\Countable', $collection);
    }

    /**
     * @covers \DCarbone\SimpleCollection::offsetExists
     * @covers \DCarbone\SimpleCollection::offsetGet
     * @covers \DCarbone\SimpleCollection::offsetSet
     * @covers \DCarbone\SimpleCollection::offsetUnset
     * @depends testObjectImplementsArrayAccess
     */
    public function testArrayAccessImplementation()
    {
        $collection = new \DCarbone\SimpleCollection();

        $this->assertFalse(isset($collection['key1']));
        $collection[] = 'value';
        $this->assertTrue(isset($collection[0]));
        $this->assertEquals('value', $collection[0]);
        unset($collection[0]);
        $this->assertNotContains('value', $collection);
        $collection['key'] = 'value';
        $this->assertArrayHasKey('key', $collection);
        $this->assertContains('value', $collection);
        unset($collection['key']);
        $this->assertArrayNotHasKey('key', $collection);
        $this->assertNotContains('value', $collection);
        $collection[] = null;
        $this->assertTrue($collection->offsetExists(1));
        $this->assertContains(null, $collection);
        unset($collection[1]);
    }

    /**
     * @covers \DCarbone\SimpleCollection::count
     * @depends testObjectImplementsCountable
     */
    public function testCountableImplementation()
    {
        $collection = new \DCarbone\SimpleCollection();

        $this->assertCount(0, $collection);
        $collection[] = 'value';
        $this->assertCount(1, $collection);
    }

    /**
     * @covers \DCarbone\SimpleCollection::current
     * @covers \DCarbone\SimpleCollection::next
     * @covers \DCarbone\SimpleCollection::key
     * @covers \DCarbone\SimpleCollection::valid
     * @covers \DCarbone\SimpleCollection::rewind
     * @covers \DCarbone\SimpleCollection::seek
     * @depends testObjectImplementsSeekableIterator
     */
    public function testIteratorImplementation()
    {
        $collection = new \DCarbone\SimpleCollection();

        $this->assertFalse($collection->valid());
        $this->assertFalse($collection->current());
        $this->assertNull($collection->key());

        $collection[] = 'value0';
        $collection[] = 'value1';
        $collection[] = 'value2';
        $collection['key3'] = 'value3';

        $this->assertTrue($collection->valid());
        $this->assertEquals('value0', $collection->current());
        $this->assertEquals(0, $collection->key());

        $collection->seek('key3');
        $this->assertTrue($collection->valid());
        $this->assertEquals('value3', $collection->current());
        $this->assertEquals('key3', $collection->key());

        $collection->rewind();
        $this->assertTrue($collection->valid());
        $this->assertEquals('value0', $collection->current());
        $this->assertEquals(0, $collection->key());
    }
}
