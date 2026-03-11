<?php
/**
 * @copyright 2013,2017 Matthew Nagi
 * @license http://opensource.org/licenses/BSD-2-Clause BSD 2-Clause License
 */

namespace Pleo\BloomFilter;

use JsonSerializable;

/**
 * Represents a bloom filter
 */
class BloomFilter implements JsonSerializable
{
    public const HASH_ALGO = 'sha1';

    public static function initFromJson(array $data): static
    {
        return new static(BitArray::initFromJson($data['bit_array']), HasherList::initFromJson($data['hashers']));
    }

    public static function init(int $approxSize, float $falsePosProb): static
    {
        $baSize = self::optimalBitArraySize($approxSize, $falsePosProb);
        $ba = BitArray::init($baSize);
        $hasherAmt = self::optimalHasherCount($approxSize, $baSize);

        $hashers = new HasherList(static::HASH_ALGO, $hasherAmt, $baSize);

        return new static($ba, $hashers);
    }

    private static function optimalBitArraySize(int $approxSetSize, float $falsePositiveProbability): int
    {
        return (int) round((($approxSetSize * log($falsePositiveProbability)) / (log(2) ** 2)) * -1);
    }

    private static function optimalHasherCount(int $approxSetSize, int $bitArraySize): int
    {
        return (int) round(($bitArraySize / $approxSetSize) * log(2));
    }

    /**
     * In general, do not use the constructor directly
     */
    public function __construct(private BitArray $ba, private HasherList $hashers)
    {
    }

    public function add(string $item): void
    {
        $vals = $this->hashers->hash($item);
        foreach ($vals as $bitLoc) {
            $this->ba[$bitLoc] = true;
        }
    }

    public function exists(string $item): bool
    {
        $exists = true;
        $vals = $this->hashers->hash($item);
        foreach ($vals as $bitLoc) {
            if (!$this->ba[$bitLoc]) {
                $exists = false;
                break;
            }
        }
        return $exists;
    }

    /**
     * @return array{bit_array: BitArray, hashers: HasherList}
     */
    public function jsonSerialize(): array
    {
        return [
            'bit_array' => $this->ba,
            'hashers' => $this->hashers,
        ];
    }
}
