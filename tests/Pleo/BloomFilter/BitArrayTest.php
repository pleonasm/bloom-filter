<?php
/**
 * @copyright 2013 Matthew Nagi
 * @license http://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 */

namespace Pleo\BloomFilter;

use PHPUnit_Framework_TestCase;
use RangeException;
use UnexpectedValueException;

class BitArrayTest extends PHPUnit_Framework_TestCase
{
    private $arr;

    public function setUp()
    {
        $this->arr = new BitArray(12);
        $this->arr[3] = true;
        $this->arr[4] = true;
        $this->arr[5] = true;
        $this->arr[11] = true;
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     * @expectedException RangeException
     */
    public function testThrowErrorIfBitArrayConstructedWithNegativeLength()
    {
        new BitArray(-3);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     * @expectedException UnexpectedValueException
     */
    public function testThrowErrorIfBitArrayConstrutectedWithNonIntegerLength()
    {
        new BitArray('big');
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     * @expectedException RangeException
     */
    public function testThrowErrorIfAccessGivenNegativeLength()
    {
        $this->arr[-3];
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     * @expectedException RangeException
     */
    public function testThrowErrorIfAccessGivenOffsetGreaterThanLength()
    {
        $this->arr[12];
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     * @expectedException UnexpectedValueException
     */
    public function testThrowErrorIfAccessGivenNonIntegerLength()
    {
        $this->arr['big'];
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testIssetReturnsFalseForValueLessThanBounds()
    {
        $this->assertFalse(isset($this->arr[-3]));
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testIssetReturnsFalseForValueGreaterThanBounds()
    {
        $this->assertFalse(isset($this->arr[18]));
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testIssetReturnsFalseForNonIntegerOffset()
    {
        $this->assertFalse(isset($this->arr['big']));
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     * @expectedException RangeException
     */
    public function testThrowErrorIfUnsetGivenNegativeLength()
    {
        unset($this->arr[-3]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     * @expectedException RangeException
     */
    public function testThrowErrorIfUnsetGivenOffsetGreaterThanLength()
    {
        unset($this->arr[12]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     * @expectedException UnexpectedValueException
     */
    public function testThrowErrorIfUnsetGivenNonIntegerLength()
    {
        unset($this->arr['big']);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testFirstBitNotSet()
    {
        $this->assertFalse($this->arr[0]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testSecondBitNotSet()
    {
        $this->assertFalse($this->arr[1]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testThirdBitNotSet()
    {
        $this->assertFalse($this->arr[2]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testFourthBitSet()
    {
        $this->assertTrue($this->arr[3]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testFifthBitSet()
    {
        $this->assertTrue($this->arr[4]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testSixthBitSet()
    {
        $this->assertTrue($this->arr[5]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testSeventhBitNotSet()
    {
        $this->assertFalse($this->arr[6]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testEightBitNotSet()
    {
        $this->assertFalse($this->arr[7]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testNinthBitNotSet()
    {
        $this->assertFalse($this->arr[8]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testTenthBitNotSet()
    {
        $this->assertFalse($this->arr[9]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testEleventhBitNotSet()
    {
        $this->assertFalse($this->arr[10]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testTwelfthBitSet()
    {
        $this->assertTrue($this->arr[11]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testUnsetReversesASet()
    {
        unset($this->arr[4]);
        $this->assertFalse($this->arr[4]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testUnsetReversesASetBeyondOneByte()
    {
        unset($this->arr[11]);
        $this->assertFalse($this->arr[11]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testSettingFalseToOffset()
    {
        $this->arr[5] = false;
        $this->assertFalse($this->arr[5]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testIssetReturnsTrueAValueThatIsSet()
    {
        $this->assertTrue(isset($this->arr[11]));
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testIssetReturnsTrueAValueThatIsNotSet()
    {
        $this->assertTrue(isset($this->arr[0]));
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BitArray
     */
    public function testCount()
    {
        $expected = 12;
        $actual = count($this->arr);
        $this->assertSame($expected, $actual);
    }
}
