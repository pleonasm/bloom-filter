<?php
/**
 * @copyright 2017 Matthew Nagi
 * @license http://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 */

namespace Pleo\BloomFilter;

use PHPUnit\Framework\TestCase;
use RangeException;
use RuntimeException;

class HasherListTest extends TestCase
{
    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\HasherList
     */
    public function testHasherListFailsWhenNegativeMaxValue()
    {
        $this->expectException(RangeException::class);
        new HasherList('sha1', 3, -2);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\HasherList
     */
    public function testHasherListFailsWhenNegativeCount()
    {
        $this->expectException(RangeException::class);
        new HasherList('sha1', -3, 200);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\HasherList
     */
    public function testInvalidHashAlgo()
    {
        $this->expectException(RuntimeException::class);
        new HasherList('this-is-not-valid', 3, 200);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\HasherList
     */
    public function testHashProducesExpectedNumbers()
    {
        $expected = [10, 85, 80];
        $sut = new HasherList('sha1', 3, 100);
        $actual = $sut->hash('foo');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\HasherList
     */
    public function testJsonSerialize()
    {
        $sut = new HasherList('sha256', 7, 100000);
        $expected = '{"algo":"sha256","count":7,"max":100000}';
        $actual = json_encode($sut);

        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\HasherList
     */
    public function testJsonDeserialize()
    {
        $sut = new HasherList('sha256', 7, 100000);
        $testVal1 = $sut->hash('meh');
        $serialized = json_encode($sut);

        $deserialized = HasherList::initFromJson(json_decode($serialized, true));
        $testVal2 = $deserialized->hash('meh');

        $this->assertEquals($testVal1, $testVal2);
    }
}
