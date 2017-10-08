<?php
/**
 * @copyright 2013,2017 Matthew Nagi
 * @license http://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 */

namespace Pleo\BloomFilter;

use PHPUnit\Framework\TestCase;

class BloomFilterTest extends TestCase
{
    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BloomFilter
     */
    public function testAddToFilter()
    {
        $ba = BitArray::init(50);
        $hasher = new HasherList('sha1', 1, 50);
        $bf = new BloomFilter($ba, $hasher);

        $bf->add('woohoo');
        $this->assertTrue($bf->exists('woohoo'));
        $this->assertFalse($bf->exists('fizzbuzz'));
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BloomFilter
     */
    public function testCleanFilterDoesNotShowItemAsExisting()
    {
        $bf = BloomFilter::init(100, 0.001);
        $this->assertFalse($bf->exists('Paul Atradies'));

        return $bf;
    }

    /**
     * @param BloomFilter $bf
     * @return BloomFilter
     * @depends testCleanFilterDoesNotShowItemAsExisting
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BloomFilter
     */
    public function testAddItemToBloomFilter($bf)
    {
        $bf->add('Paul Atradies');
        $this->assertTrue($bf->exists('Paul Atradies'));

        return $bf;
    }

    /**
     * @group BloomFilter
     * @covers \Pleo\BloomFilter\BloomFilter
     */
    public function testJsonSerialize()
    {
        $sut = BloomFilter::init(100, 0.001);
        $sut->add('foo');
        $sut->add('bar');
        $serialized = json_encode($sut);

        $sut2 = BloomFilter::initFromJson(json_decode($serialized, true));
        $this->assertTrue($sut2->exists('foo'));
        $this->assertTrue($sut2->exists('bar'));
        $this->assertFalse($sut2->exists('baz'));
    }
}
