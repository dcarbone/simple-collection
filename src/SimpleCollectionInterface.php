<?php namespace DCarbone;

/**
 * Interface SimpleCollectionInterface
 * @package DCarbone
 */
interface SimpleCollectionInterface extends \SeekableIterator, \ArrayAccess, \Countable
{
    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct(array $data = array());

    /**
     * @param mixed $name
     * @return mixed
     * @throws \OutOfBoundsException
     */
    public function &__get($name);

    /**
     * @param string|int $name
     * @param mixed $value
     */
    public function __set($name, $value);

    /**
     * @return array
     */
    public function keys();

    /**
     * @return array
     */
    public function values();

    /**
     * Executes array_search on internal storage array.
     *
     * Please refer to PHP docs for usage information.
     * @link http://php.net/manual/en/function.array-search.php
     *
     * @param mixed $value
     * @param bool $strict
     * @return mixed
     */
    public function search($value, $strict = false);

    /**
     * Moves internal storage array pointer to last index and returns value
     *
     * @return mixed
     */
    public function end();

    /**
     * @return mixed
     */
    public function firstValue();

    /**
     * @return mixed
     */
    public function lastValue();

    /**
     * @return int|null|string
     */
    public function firstKey();

    /**
     * @return int|null|string
     */
    public function lastKey();

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @return array
     */
    public function getArrayCopy();
}