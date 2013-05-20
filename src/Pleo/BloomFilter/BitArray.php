<?php
/**
 * @license https://raw.github.com/pleonasm/bloom-filter/master/LICENSE
 */

namespace Pleo\BloomFilter;

use ArrayAccess;
use Countable;
use RangeException;
use UnexpectedValueException;

/**
 * Provides PHP access semantics to an arbitrary length array of bits
 */
class BitArray implements ArrayAccess, Countable
{
    const BITS_IN_BYTE = 8;

    /**
     * @var int
     */
    private $length;

    /**
     * @var string
     */
    private $data;

    /**
     * @param int $length The length of the array
     * @throws UnexpectedValueException
     * @throws RangeException
     */
    public function __construct($length)
    {
        $this->isOffset($length, false);
        $this->length = $length;

        $this->data = str_repeat(chr(0), $this->length);
    }

    /**
     * @param int $offset
     * @throws UnexpectedValueException
     * @throws RangeException
     * @return bool
     */
    public function offsetExists($offset)
    {
        if (!is_int($offset)) {
            return false;
        }

        if ($offset < 0) {
            return false;
        }

        if ($offset > $this->length - 1) {
            return false;
        }

        return true;
    }

    /**
     * @param int $offset
     * @throws UnexpectedValueException
     * @throws RangeException
     * @return bool
     */
    public function offsetGet($offset)
    {
        $this->isOffset($offset);

        $byte = $this->offsetToByte($offset);
        $byte = ord($this->data[$byte]);
        $bit = (bool) ($this->finalBitPos($offset) & $byte);

        return $bit;
    }

    /**
     * @param int $offset
     * @param bool $value
     * @throws UnexpectedValueException
     * @throws RangeException
     * @return null
     */
    public function offsetSet($offset, $value)
    {
        $this->isOffset($offset);
        $value = (bool) $value;

        $obyte = $this->offsetToByte($offset);
        $byte = ord($this->data[$obyte]);
        $pos = $this->finalBitPos($offset);

        if ($value) {
            $byte |= $pos;
        } else {
            $byte &= 0xFF ^ $pos;
        }

        $this->data[$obyte] = chr($byte);
    }

    /**
     * @param int $offset
     * @throws UnexpectedValueException
     * @throws RangeException
     * @return null
     */
    public function offsetUnset($offset)
    {
        $this->offsetSet($offset, false);
    }

    /**
     * Returns the length (amount of bits) of the bit array
     *
     * @return int
     */
    public function count()
    {
        return $this->length;
    }

    /**
     * @param mixed $val
     * @param bool $checkUpperBound
     * @throws RangeException
     * @throws UnexpectedValueException
     * @return null
     */
    private function isOffset($val, $checkUpperBound = true)
    {
        if (!is_int($val)) {
            throw new UnexpectedValueException('Value must be an integer.');
        }

        if ($val < 0) {
            throw new RangeException('Value must be greater than zero.');
        }

        if ($checkUpperBound && $val >= $this->length) {
            throw new RangeException('Value must be less than array length - 1.');
        }
    }

    /**
     * @param int $offset
     * @return int
     */
    private function offsetToByte($offset)
    {
        return (int) floor($offset / self::BITS_IN_BYTE);
    }

    /**
     * @param int $offset
     * @return int
     */
    private function finalBitPos($offset)
    {
        return (int) pow(2, $offset % self::BITS_IN_BYTE);
    }
}
