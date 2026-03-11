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

/**
 * @group BloomFilter
 * @covers \Pleo\BloomFilter\BitArray
 */
class BitArrayTest extends TestCase
{
    private BitArray $arr;

    protected function setUp(): void
    {
        $this->arr = BitArray::init(12);
        $this->arr[3] = true;
        $this->arr[4] = true;
        $this->arr[5] = true;
        $this->arr[11] = true;
    }

    public function testThrowErrorIfBitArrayInitializedWithNegativeLength()
    {
        $this->expectException(RangeException::class);
        BitArray::init(-3);
    }

    public function testThrowErrorIfBitArrayConstrutectedWithNonIntegerLength()
    {
        $this->expectException(TypeError::class);
        BitArray::init('big');
    }

    public function testThrowErrorIfAccessGivenNegativeLength()
    {
        $this->expectException(RangeException::class);
        $this->arr[-3];
    }

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

    public function testThrowErrorIfAccessGivenNonIntegerLength()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->arr['big'];
    }

    public function testIssetReturnsFalseForValueLessThanBounds()
    {
        $this->assertFalse(isset($this->arr[-3]));
    }

    public function testIssetReturnsFalseForValueGreaterThanBounds()
    {
        $this->assertFalse(isset($this->arr[18]));
    }

    public function testIssetReturnsFalseForNonIntegerOffset()
    {
        $this->assertFalse(isset($this->arr['big']));
    }

    public function testThrowErrorIfUnsetGivenNegativeLength()
    {
        $this->expectException(RangeException::class);
        unset($this->arr[-3]);
    }

    public function testThrowErrorIfUnsetGivenOffsetGreaterThanLength()
    {
        $this->expectException(RangeException::class);
        unset($this->arr[12]);
    }

    public function testThrowErrorIfUnsetGivenNonIntegerLength()
    {
        $this->expectException(UnexpectedValueException::class);
        unset($this->arr['big']);
    }

    public function testFirstBitNotSet()
    {
        $this->assertFalse($this->arr[0]);
    }

    public function testSecondBitNotSet()
    {
        $this->assertFalse($this->arr[1]);
    }

    public function testThirdBitNotSet()
    {
        $this->assertFalse($this->arr[2]);
    }

    public function testFourthBitSet()
    {
        $this->assertTrue($this->arr[3]);
    }

    public function testFifthBitSet()
    {
        $this->assertTrue($this->arr[4]);
    }

    public function testSixthBitSet()
    {
        $this->assertTrue($this->arr[5]);
    }

    public function testSeventhBitNotSet()
    {
        $this->assertFalse($this->arr[6]);
    }

    public function testEightBitNotSet()
    {
        $this->assertFalse($this->arr[7]);
    }

    public function testNinthBitNotSet()
    {
        $this->assertFalse($this->arr[8]);
    }

    public function testTenthBitNotSet()
    {
        $this->assertFalse($this->arr[9]);
    }

    public function testEleventhBitNotSet()
    {
        $this->assertFalse($this->arr[10]);
    }

    public function testTwelfthBitSet()
    {
        $this->assertTrue($this->arr[11]);
    }

    public function testUnsetReversesASet()
    {
        unset($this->arr[4]);
        $this->assertFalse($this->arr[4]);
    }

    public function testUnsetReversesASetBeyondOneByte()
    {
        unset($this->arr[11]);
        $this->assertFalse($this->arr[11]);
    }

    public function testSettingFalseToOffset()
    {
        $this->arr[5] = false;
        $this->assertFalse($this->arr[5]);
    }

    public function testIssetReturnsTrueAValueThatIsSet()
    {
        $this->assertTrue(isset($this->arr[11]));
    }

    public function testIssetReturnsTrueAValueThatIsNotSet()
    {
        $this->assertTrue(isset($this->arr[0]));
    }

    public function testCount()
    {
        $expected = 12;
        $actual = count($this->arr);
        $this->assertSame($expected, $actual);
    }

    public function testByteLength()
    {
        $expected = 2;
        $actual = $this->arr->byteLength();
        $this->assertSame($expected, $actual);
    }

    public function testJsonSerialize()
    {
        $expected = '{"len":12,"arr":"OAg="}';
        $actual = json_encode($this->arr);
        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function testJsonDeserialize()
    {
        $expected = serialize($this->arr);
        $actual = serialize(BitArray::initFromJson(json_decode(json_encode($this->arr), true)));
        $this->assertSame($expected, $actual);
    }

    public function testNegativeJsonDeserialize()
    {
        $expected = serialize($this->arr);
        $tmp = json_decode(json_encode($this->arr), true);
        $tmp['arr'] = base64_encode(chr(0) . chr(0));
        $actual = serialize(BitArray::initFromJson($tmp));
        $this->assertNotSame($expected, $actual);
    }
}
