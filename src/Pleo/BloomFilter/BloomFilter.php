<?php
/**
 * @copyright 2013 Matthew Nagi
 * @license http://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 */

namespace Pleo\BloomFilter;

use JsonSerializable;

/**
 * Represents a bloom filter
 */
class BloomFilter implements JsonSerializable
{
    /**
     * @var BitArray
     */
    private $ba;

    /**
     * @param int $approxSize
     * @param float $falsePosProb
     * @return BloomFilter
     */
    public static function create($approxSize, $falsePosProb)
    {
        $baSize = self::optimalBitArraySize($approxSize, $falsePosProb);
        $ba = new BitArray($baSize);
        $hasherAmt = self::optimalHasherCount($approxSize, $baSize);
        $hashers = [];
        for ($i = 0; $i < $hasherAmt; $i++) {
            $hashers[] = self::createHasher('crc32', $i);
        }
        return new self($ba, $hashers);
    }

    /**
     * @param int $approxSetSize
     * @param float $falsePositiveProbability
     * @return int
     */
    private static function optimalBitArraySize($approxSetSize, $falsePositiveProbability)
    {
        return (int) round((($approxSetSize * log($falsePositiveProbability)) / pow(log(2), 2)) * -1);
    }

    /**
     * @param int $approxSetSize
     * @param int $bitArraySize
     * @return int
     */
    private static function optimalHasherCount($approxSetSize, $bitArraySize)
    {
        return (int) round(($bitArraySize / $approxSetSize) * log(2));
    }

    /**
     * @param string $algo
     * @param string|int $seed
     * @return callable
     */
    private static function createHasher($algo, $seed)
    {
        return function ($item, $baSize) use ($algo, $seed) {
            return abs(hexdec(hash($algo, $seed . $item))) % ($baSize - 1);
        };
    }

    /**
     * In general, do not use the constructor directly
     *
     * @param BitArray $ba
     * @param callable[] $hashers
     */
    public function __construct(BitArray $ba, array $hashers)
    {
        $this->ba = $ba;
        $this->hashers = $hashers;
    }

    /**
     * @param string $item
     * @return null
     */
    public function add($item)
    {
        foreach ($this->hashers as $hasher) {
            $res = call_user_func($hasher, $item, $this->ba->count());
            $this->ba[$res] = true;
        }
    }

    /**
     * @param string $item
     * @return bool
     */
    public function exists($item)
    {
        $exists = true;
        foreach ($this->hashers as $hasher) {
            $res = call_user_func($hasher, $item, $this->ba->count());
            if (!$this->ba[$res]) {
                $exists = false;
                break;
            }
        }
        return $exists;
    }

    /**
     */
    public function jsonSerialize()
    {

    }
}
