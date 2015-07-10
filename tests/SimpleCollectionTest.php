<?php

/**
 * Class SimpleCollectionTest
 */
class SimpleCollectionTest extends \PHPUnit_Framework_TestCase
{
    //<editor-fold desc="ConstructorTests">
    /**
     * @covers \DCarbone\SimpleCollection::__construct
     * @return \DCarbone\SimpleCollection
     */
    public function testCanConstructObjectWithNoArguments()
    {
        $collection = new \DCarbone\SimpleCollection();
        $this->assertInstanceOf('\\DCarbone\\SimpleCollection', $collection);

        return $collection;
    }

    /**
     * @covers \DCarbone\SimpleCollection::__construct
     * @return \DCarbone\SimpleCollection
     */
    public function testCanConstructObjectWithArrayParameter()
    {
        $collection = new \DCarbone\SimpleCollection(array(
            'value0',
            'value1',
            'key2' => 'value2',
            4 => 'value3',
            '3' => 4,
        ));
        $this->assertInstanceOf('\\DCarbone\\SimpleCollection', $collection);

        return $collection;
    }
    //</editor-fold>

    //<editor-fold desc="DoesImplementTests">
    /**
     * @depends testCanConstructObjectWithNoArguments
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testObjectImplementsArrayAccess(\DCarbone\SimpleCollection $collection)
    {
        $this->assertInstanceOf('\\ArrayAccess', $collection);
    }

    /**
     * @depends testCanConstructObjectWithNoArguments
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testObjectImplementsSeekableIterator(\DCarbone\SimpleCollection $collection)
    {
        $this->assertInstanceOf('\\SeekableIterator', $collection);
    }

    /**
     * @depends testCanConstructObjectWithNoArguments
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testObjectImplementsCountable(\DCarbone\SimpleCollection $collection)
    {
        $this->assertInstanceOf('\\Countable', $collection);
    }

    /**
     * @depends testCanConstructObjectWithNoArguments
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testObjectImplementsFirstLastValueKeyMethods(\DCarbone\SimpleCollection $collection)
    {
        $this->assertObjectHasAttribute('_firstKey', $collection);
        $this->assertObjectHasAttribute('_lastKey', $collection);
        $this->assertObjectHasAttribute('_modified', $collection);
        $this->assertTrue(method_exists($collection, '_updateFirstLastKeys'), '_updateKeys method not implemented');
        $this->assertTrue(is_callable(array($collection, 'firstValue'), false), 'firstValue method not callable');
        $this->assertTrue(is_callable(array($collection, 'lastValue'), false), 'lastValue method not callable');
        $this->assertTrue(is_callable(array($collection, 'firstKey'), false), 'firstKey method not callable');
        $this->assertTrue(is_callable(array($collection, 'lastKey'), false), 'lastKey method not callable');
    }

    /**
     * @depends testCanConstructObjectWithNoArguments
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testObjectImplementsIsEmptyMethod(\DCarbone\SimpleCollection $collection)
    {
        $this->assertTrue(is_callable(array($collection, 'isEmpty')), 'isEmpty method not callable');
    }

    /**
     * @depends testCanConstructObjectWithNoArguments
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testObjectImplementsEndMethod(\DCarbone\SimpleCollection $collection)
    {
        $this->assertTrue(is_callable(array($collection, 'end'), false));
    }

    /**
     * @depends testCanConstructObjectWithNoArguments
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testObjectImplementsKeysMethod(\DCarbone\SimpleCollection $collection)
    {
        $this->assertTrue(is_callable(array($collection, 'keys'), false));
    }

    /**
     * @depends testCanConstructObjectWithNoArguments
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testObjectImplementsValuesMethod(\DCarbone\SimpleCollection $collection)
    {
        $this->assertTrue(is_callable(array($collection, 'values'), false));
    }

    /**
     * @depends testCanConstructObjectWithNoArguments
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testObjectImplementsSearchMethod(\DCarbone\SimpleCollection $collection)
    {
        $this->assertTrue(is_callable(array($collection, 'search'), false));
    }
    //</editor-fold>

    //<editor-fold desc="ArrayAccessTests">
    /**
     * @covers \DCarbone\SimpleCollection::offsetExists
     * @covers \DCarbone\SimpleCollection::offsetGet
     * @covers \DCarbone\SimpleCollection::offsetSet
     * @covers \DCarbone\SimpleCollection::offsetUnset
     * @depends testObjectImplementsArrayAccess
     */
    public function testBasicArrayAccessImplementation()
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
    }
    //</editor-fold>

    //<editor-fold desc="CountableTests">
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
    //</editor-fold>

    //<editor-fold desc="SeekableIteratorTests">
    /**
     * @covers \DCarbone\SimpleCollection::current
     * @covers \DCarbone\SimpleCollection::next
     * @covers \DCarbone\SimpleCollection::key
     * @covers \DCarbone\SimpleCollection::valid
     * @covers \DCarbone\SimpleCollection::rewind
     * @covers \DCarbone\SimpleCollection::seek
     * @depends testObjectImplementsSeekableIterator
     */
    public function testSeekableIteratorImplementation()
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

        $collection->seek(2);
        $this->assertTrue($collection->valid());
        $this->assertEquals('value2', $collection->current());
        $this->assertEquals(2, $collection->key());

        $collection->rewind();
        $this->assertTrue($collection->valid());
        $this->assertEquals('value0', $collection->current());
        $this->assertEquals(0, $collection->key());

        $collection->next();
        $this->assertTrue($collection->valid());
        $this->assertEquals('value1', $collection->current());
        $this->assertEquals(1, $collection->key());
    }
    //</editor-fold>

    //<editor-fold desc="IsEmptyMethodTests">
    /**
     * @covers \DCarbone\SimpleCollection::isEmpty
     * @depends testObjectImplementsIsEmptyMethod
     */
    public function testIsEmptyMethodOnPopulatedObject()
    {
        $collection = new \DCarbone\SimpleCollection(array('value'));
        $this->assertFalse($collection->isEmpty());
    }

    /**
     * @covers \DCarbone\SimpleCollection::isEmpty
     * @depends testObjectImplementsIsEmptyMethod
     */
    public function testIsEmptyMethodOnEmptyObject()
    {
        $collection = new \DCarbone\SimpleCollection();
        $this->assertTrue($collection->isEmpty());
    }
    //</editor-fold>

    //<editor-fold desc="FirstLastValueMethodsTests">
    /**
     * @covers \DCarbone\SimpleCollection::firstValue
     * @covers \DCarbone\SimpleCollection::lastValue
     * @covers \DCarbone\SimpleCollection::_updateFirstLastKeys
     * @depends testObjectImplementsIsEmptyMethod
     * @depends testObjectImplementsFirstLastValueKeyMethods
     */
    public function testFirstLastValueMethodsOnPopulatedObject()
    {
        $collection = new \DCarbone\SimpleCollection(array(
            'first',
            'middle',
            7 => 'last'
        ));

        $first = $collection->firstValue();
        $this->assertEquals('first', $first);
        $last = $collection->lastValue();
        $this->assertEquals('last', $last);
    }

    /**
     * @covers \DCarbone\SimpleCollection::firstValue
     * @covers \DCarbone\SimpleCollection::lastValue
     * @covers \DCarbone\SimpleCollection::_updateFirstLastKeys
     * @depends testObjectImplementsIsEmptyMethod
     * @depends testObjectImplementsFirstLastValueKeyMethods
     */
    public function testFirstLastValueMethodsOnEmptyObject()
    {
        $collection = new \DCarbone\SimpleCollection();

        $first = $collection->firstValue();
        $this->assertNull($first);
        $last = $collection->lastValue();
        $this->assertNull($last);
    }
    //</editor-fold>

    //<editor-fold desc="FirstLastKeyMethodsTests">
    /**
     * @covers \DCarbone\SimpleCollection::firstKey
     * @covers \DCarbone\SimpleCollection::lastKey
     * @covers \DCarbone\SimpleCollection::_updateFirstLastKeys
     * @depends testObjectImplementsIsEmptyMethod
     * @depends testObjectImplementsFirstLastValueKeyMethods
     */
    public function testFirstLastKeyMethodsOnPopulatedObject()
    {
        $collection = new \DCarbone\SimpleCollection(array(
            'first',
            'middle',
            7 => 'last',
        ));

        $first = $collection->firstKey();
        $this->assertEquals(0, $first);
        $last = $collection->lastKey();
        $this->assertEquals(7, $last);
    }

    /**
     * @covers \DCarbone\SimpleCollection::firstKey
     * @covers \DCarbone\SimpleCollection::lastKey
     * @covers \DCarbone\SimpleCollection::_updateFirstLastKeys
     * @depends testObjectImplementsIsEmptyMethod
     * @depends testObjectImplementsFirstLastValueKeyMethods
     */
    public function testFirstLastKeyMethodsOnEmptyObject()
    {
        $collection = new \DCarbone\SimpleCollection();

        $first = $collection->firstKey();
        $this->assertNull($first);
        $last = $collection->lastKey();
        $this->assertNull($last);
    }
    //</editor-fold>

    //<editor-fold desc="EndMethodTests">
    /**
     * @covers \DCarbone\SimpleCollection::end
     * @depends testCanConstructObjectWithNoArguments
     * @depends testObjectImplementsEndMethod
     * @depends testSeekableIteratorImplementation
     * @depends testFirstLastKeyMethodsOnEmptyObject
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testEndMethodOnEmptyObject(\DCarbone\SimpleCollection $collection)
    {
        $this->assertFalse($collection->valid());
        $collection->end();
        $this->assertFalse($collection->valid());
    }

    /**
     * @covers \DCarbone\SimpleCollection::end
     * @depends testCanConstructObjectWithArrayParameter
     * @depends testObjectImplementsEndMethod
     * @depends testSeekableIteratorImplementation
     * @depends testFirstLastKeyMethodsOnPopulatedObject
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testEndMethodOnPopulatedObject(\DCarbone\SimpleCollection $collection)
    {
        $this->assertTrue($collection->valid());
        $this->assertEquals('value0', $collection->current());
        $this->assertEquals(0, $collection->key());

        $collection->end();
        $this->assertTrue($collection->valid());
        $this->assertEquals(4, $collection->current());
        $this->assertEquals('3', $collection->key());
    }
    //</editor-fold>

    //<editor-fold desc="KeysMethodTests">
    /**
     * @covers  \DCarbone\SimpleCollection::keys
     * @depends testCanConstructObjectWithArrayParameter
     * @depends testObjectImplementsKeysMethod
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testKeysMethodOnPopulatedObject(\DCarbone\SimpleCollection $collection)
    {
        $keys = $collection->keys();
        $this->assertInternalType('array', $keys);
        $this->assertCount(5, $keys);
        $this->assertContains(4, $keys);
        $this->assertContains('key2', $keys);
    }

    /**
     * @covers \DCarbone\SimpleCollection::keys
     * @depends testCanConstructObjectWithNoArguments
     * @depends testObjectImplementsKeysMethod
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testKeysMethodOnEmptyObject(\DCarbone\SimpleCollection $collection)
    {
        $keys = $collection->keys();
        $this->assertInternalType('array', $keys);
        $this->assertCount(0, $keys);
    }
    //</editor-fold>

    //<editor-fold desc="ValuesMethodTests">
    /**
     * @covers \DCarbone\SimpleCollection::values
     * @depends testCanConstructObjectWithArrayParameter
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testValuesMethodOnPopulatedObject(\DCarbone\SimpleCollection $collection)
    {
        $values = $collection->values();
        $this->assertInternalType('array', $values);
        $this->assertCount(5, $values);
        $this->assertContains('value1', $values);
        $this->assertContains('value3', $values);
    }

    /**
     * @covers \DCarbone\SimpleCollection::values
     * @depends testCanConstructObjectWithNoArguments
     * @depends testObjectImplementsValuesMethod
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testValuesMethodOnEmptyObject(\DCarbone\SimpleCollection $collection)
    {
        $values = $collection->values();
        $this->assertInternalType('array', $values);
        $this->assertCount(0, $values);
    }
    //</editor-fold>

    /**
     * @covers \DCarbone\SimpleCollection::search
     * @depends testCanConstructObjectWithNoArguments
     * @depends testObjectImplementsSearchMethod
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testLooseSearchOnEmptyObject(\DCarbone\SimpleCollection $collection)
    {
        $this->assertFalse($collection->search('sandwiches'));
    }

    /**
     * @covers \DCarbone\SimpleCollection::search
     * @depends testCanConstructObjectWithArrayParameter
     * @depends testObjectImplementsSearchMethod
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testLooseSearchOnPopulatedObject(\DCarbone\SimpleCollection $collection)
    {
        $this->assertEquals('3', $collection->search('4'));
    }

    /**
     * @covers \DCarbone\SimpleCollection::search
     * @depends testCanConstructObjectWithNoArguments
     * @depends testObjectImplementsSearchMethod
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testStrictSearchOnEmptyObject(\DCarbone\SimpleCollection $collection)
    {
        $this->assertFalse($collection->search('4', true));
    }

    /**
     * @covers \DCarbone\SimpleCollection::search
     * @depends testCanConstructObjectWithArrayParameter
     * @depends testObjectImplementsSearchMethod
     * @param \DCarbone\SimpleCollection $collection
     */
    public function testStrictSearchOnPopulatedObject(\DCarbone\SimpleCollection $collection)
    {
        $this->assertFalse($collection->search('4', true));
    }
}
