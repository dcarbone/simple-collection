<?php namespace DCarbone;

/**
 * Class SimpleCollection
 * @package DCarbone
 */
class SimpleCollection implements \SeekableIterator, \ArrayAccess, \Countable
{
    /** @var array */
    private $_storage = array();

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->_storage = $data;
    }

    /**
     * @param mixed $name
     * @return mixed
     * @throws \OutOfBoundsException
     */
    function &__get($name)
    {
        if ($this->offsetExists($name))
            return $this->_storage[$name];

        throw new \OutOfBoundsException(sprintf('Key "%s" does not exist in this collection.', $name));
    }

    /**
     * @param mixed $name
     * @param mixed $value
     */
    function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * (PHP 5 >= 5.0.0)
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->_storage);
    }

    /**
     * (PHP 5 >= 5.0.0)
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        next($this->_storage);
    }

    /**
     * (PHP 5 >= 5.0.0)
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->_storage);
    }

    /**
     * (PHP 5 >= 5.0.0)
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean
     *
     * The return value will be casted to boolean and then evaluated.
     */
    public function valid()
    {
        return !(null === key($this->_storage) && false === current($this->_storage));
    }

    /**
     * (PHP 5 >= 5.0.0)
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        reset($this->_storage);
    }

    /**
     * (PHP 5 >= 5.1.0)
     * Seeks to a position
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @param int|string $position The position to seek to.
     * @return void
     */
    public function seek($position)
    {
        while (($key = key($this->_storage)) !== null && $key !== $position)
        {
            next($this->_storage);
        }
    }

    /**
     * (PHP 5 >= 5.0.0)
     * Whether a offset exists
     * @internal
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset An offset to check for.
     * @return boolean true on success or false on failure.
     *
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return isset($this->_storage[$offset]) || array_key_exists($offset, $this->_storage);
    }

    /**
     * (PHP 5 >= 5.0.0)
     * Offset to retrieve
     * @internal
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset The offset to retrieve.
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset))
            return $this->_storage[$offset];

        trigger_error(vsprintf(
            '%s::offsetGet - Requested offset "%s" does not exist in this collection.',
            array(get_class($this), $offset)
        ), E_NOTICE);

        return null;
    }

    /**
     * (PHP 5 >= 5.0.0)
     * Offset to set
     * @internal
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset)
            $this->_storage[] = $value;
        else
            $this->_storage[$offset] = $value;
    }

    /**
     * (PHP 5 >= 5.0.0)
     * Offset to unset
     * @internal
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset The offset to unset.
     * @return void
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset))
            unset($this->_storage[$offset]);
    }

    /**
     * (PHP 5 >= 5.1.0)
     * Count elements of an object
     * @internal
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->_storage);
    }

    /**
     * @return array
     */
    public function keys()
    {
        return array_keys($this->_storage);
    }

    /**
     * @return array
     */
    public function values()
    {
        return array_values($this->_storage);
    }

    /**
     * @param mixed $value
     * @param bool|false $strict
     * @return mixed
     */
    public function search($value, $strict = false)
    {
        return array_search($this->_storage, $value, $strict);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return @reset(array_values($this->_storage));
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return @reset(array_values($this->_storage));
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === count($this);
    }
}