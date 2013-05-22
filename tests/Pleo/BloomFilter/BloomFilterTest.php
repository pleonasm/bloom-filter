<?php
/**
 * @copyright 2013 Matthew Nagi
 * @license http://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 */

namespace Pleo\BloomFilter;

use PHPUnit_Framework_TestCase;

class BloomFilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BloomFilter
     */
    public function testAddToFilter()
    {
        $cb = function ($item, $basize) use (&$arg1, &$arg2) {
            $arg1 = $item;
            $arg2 = $basize;
            return 3;
        };
        $ba = new BitArray(50);
        $bf = new BloomFilter($ba, array($cb));

        $bf->add('woohoo');

        $this->assertSame('woohoo', $arg1);
        $this->assertSame(50, $arg2);
        $this->assertTrue($ba[3]);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BloomFilter
     */
    public function testExistsInFilter()
    {
        $cb = function ($item, $basize) use (&$arg1, &$arg2, &$counter) {
            $arg1 = $item;
            $arg2 = $basize;
            $counter++;
            if ($counter > 1) {
                return 4;
            } else {
                return 3;
            }
        };
        $ba = new BitArray(50);
        $bf = new BloomFilter($ba, array($cb));

        $bf->add('woohoo');
        $actual = $bf->exists('asdf');

        $this->assertSame('asdf', $arg1);
        $this->assertSame(50, $arg2);
        $this->assertFalse($actual);
    }

    /**
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BloomFilter
     */
    public function testCleanFilterDoesNotShowItemAsExisting()
    {
        $bf = BloomFilter::create(100, 0.001);
        $this->assertFalse($bf->exists('Paul Atradies'));

        return $bf;
    }

    /**
     * @depends testCleanFilterDoesNotShowItemAsExisting
     * @group BloomFilter
     * @covers Pleo\BloomFilter\BloomFilter
     */
    public function testAddItemToBloomFilter($bf)
    {
        $bf->add('Paul Atradies');
        $this->assertTrue($bf->exists('Paul Atradies'));

        return $bf;
    }
}
