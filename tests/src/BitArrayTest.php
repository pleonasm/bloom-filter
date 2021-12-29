<?php
/**
 * @copyright 2013-2020 Matthew Nagi
 * @license http://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 */

namespace Pleo\BloomFilter;

use PHPUnit\Framework\TestCase;
use RangeException;
use UnexpectedValueException;
use TypeError;

class BitArrayTest extends TestCase
{
    /**
     * @var BitArray
     */
    private $arr;

    protected function setUp(): void
    {
        $this->arr = BitArray::init(12);
        $this->arr[3] = true;
        $this->arr[4] = true;
        $this->arr[5] = true;
        $this->arr[11] = true;
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testThrowErrorIfBitArrayInitializedWithNegativeLength()
    {
        $this->expectException(RangeException::class);
        BitArray::init(-3);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testThrowErrorIfBitArrayConstrutectedWithNonIntegerLength()
    {
        $this->expectException(TypeError::class);
        BitArray::init('big');
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testThrowErrorIfAccessGivenNegativeLength()
    {
        $this->expectException(RangeException::class);
        $this->arr[-3];
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testThrowErrorIfAccessGivenOffsetGreaterThanLength()
    {
        try {
            $this->arr[12];
        } catch (RangeException $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testThrowErrorIfAccessGivenNonIntegerLength()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->arr['big'];
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testIssetReturnsFalseForValueLessThanBounds()
    {
        $this->assertFalse(isset($this->arr[-3]));
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testIssetReturnsFalseForValueGreaterThanBounds()
    {
        $this->assertFalse(isset($this->arr[18]));
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testIssetReturnsFalseForNonIntegerOffset()
    {
        $this->assertFalse(isset($this->arr['big']));
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testThrowErrorIfUnsetGivenNegativeLength()
    {
        $this->expectException(RangeException::class);
        unset($this->arr[-3]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testThrowErrorIfUnsetGivenOffsetGreaterThanLength()
    {
        $this->expectException(RangeException::class);
        unset($this->arr[12]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testThrowErrorIfUnsetGivenNonIntegerLength()
    {
        $this->expectException(UnexpectedValueException::class);
        unset($this->arr['big']);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testFirstBitNotSet()
    {
        $this->assertFalse($this->arr[0]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testSecondBitNotSet()
    {
        $this->assertFalse($this->arr[1]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testThirdBitNotSet()
    {
        $this->assertFalse($this->arr[2]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testFourthBitSet()
    {
        $this->assertTrue($this->arr[3]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testFifthBitSet()
    {
        $this->assertTrue($this->arr[4]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testSixthBitSet()
    {
        $this->assertTrue($this->arr[5]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testSeventhBitNotSet()
    {
        $this->assertFalse($this->arr[6]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testEightBitNotSet()
    {
        $this->assertFalse($this->arr[7]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testNinthBitNotSet()
    {
        $this->assertFalse($this->arr[8]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testTenthBitNotSet()
    {
        $this->assertFalse($this->arr[9]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testEleventhBitNotSet()
    {
        $this->assertFalse($this->arr[10]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testTwelfthBitSet()
    {
        $this->assertTrue($this->arr[11]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testUnsetReversesASet()
    {
        unset($this->arr[4]);
        $this->assertFalse($this->arr[4]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testUnsetReversesASetBeyondOneByte()
    {
        unset($this->arr[11]);
        $this->assertFalse($this->arr[11]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testSettingFalseToOffset()
    {
        $this->arr[5] = false;
        $this->assertFalse($this->arr[5]);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testIssetReturnsTrueAValueThatIsSet()
    {
        $this->assertTrue(isset($this->arr[11]));
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testIssetReturnsTrueAValueThatIsNotSet()
    {
        $this->assertTrue(isset($this->arr[0]));
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testCount()
    {
        $expected = 12;
        $actual = count($this->arr);
        $this->assertSame($expected, $actual);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testByteLength()
    {
        $expected = 2;
        $actual = $this->arr->byteLength();
        $this->assertSame($expected, $actual);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testJsonSerialize()
    {
        $expected = '{"len":12,"arr":"OAg="}';
        $actual = json_encode($this->arr);
        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testJsonDeserialize()
    {
        $expected = serialize($this->arr);
        $actual = serialize(BitArray::initFromJson(json_decode(json_encode($this->arr), true)));
        $this->assertSame($expected, $actual);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BitArray
     */
    public function testNegativeJsonDeserialize()
    {
        $expected = serialize($this->arr);
        $tmp = json_decode(json_encode($this->arr), true);
        $tmp['arr'] = base64_encode(chr(0) . chr(0));
        $actual = serialize(BitArray::initFromJson($tmp));
        $this->assertNotSame($expected, $actual);
    }
}
