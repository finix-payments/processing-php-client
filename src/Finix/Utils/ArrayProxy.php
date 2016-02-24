<?php

namespace Payline\Utils;

use ArrayAccess;
use ArrayObject;
use Countable;
use IteratorAggregate;

class ArrayProxy implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * proxy the object accessed via __get() and __set()
     *
     * @var array
     */
    private $proxy;


    public function __construct(array $arrayToProxy = null)
    {
        $this->proxy = (array) $arrayToProxy;
    }

    /**
     * getIterator allows us to use foreach on a Proxy object
     */
    public function getIterator()
    {
        $arrayObject = new ArrayObject($this->proxy);
        return $arrayObject->getIterator();
    }
    /**
     * __get If a nonexistent property of a Proxy object is called, this
     * function checks to see if the property corresponds to a key of
     * $this->proxy, and returns that, otherwise it returns null.
     *
     * @param  string $key a possible key to the $proxy array
     * @return mixed  null or the value of the existing key
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->proxy)) {
            return $this->proxy[$key];
        } else {
            return null;
        }
    }
    /**
     * __set If a property of a Proxy object is set, this function sets the
     * appropriate key in the $proxy array, allowing it to be retrieved
     * later.
     *
     * @param  string $key   a new (or existing) key to the $proxy array
     * @param  mixed  $value the value to set the key of $proxy to
     * @return void
     */
    public function __set($key, $value)
    {
        $this->proxy[$key] = $value;
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->proxy);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->proxy[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->proxy[$offset];
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->proxy[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->proxy[$offset]);
    }

    /**
     * Because array_key_exists() does not work on an interface. You can use isset() instead
     * if you want to check if the key exists + is not null
     * @param string $name the name of the attribute to test for existence
     * @return bool
     */
    public function has_key($name) {
        return array_key_exists($name, $this->proxy);
    }
}