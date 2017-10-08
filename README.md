# A Bloom Filter for PHP #

[![Build Status](https://travis-ci.org/pleonasm/bloom-filter.png?branch=master)](https://travis-ci.org/pleonasm/bloom-filter)
[![Coverage Status](https://coveralls.io/repos/pleonasm/bloom-filter/badge.png)](https://coveralls.io/r/pleonasm/bloom-filter)

This is a well tested implementation of a [Bloom Filter](http://en.wikipedia.org/wiki/Bloom_filter)
for PHP. It has the following features:

1. Efficient memory use for bit array (as efficient as PHP can be anyway).
2. A way to get a raw version of the filter for transport to other systems.
3. Ability to restore said raw filter back into a usable one.
4. Auto-calculates optimal hashing and bit array size based on desired set size
   and false-positive probability.
5. Auto-generates hashing functions as needed.

## Installation ##

Install via [Composer](http://getcomposer.org) (make sure you have composer in
your path or in your project).

Put the following in your package.json:

```javascript
{
    "require": {
        "pleonasm/bloom-filter": "*"
    }
}
```

Run `composer install`.

## Usage ##

```php
<?php
use Pleo\BloomFilter\BloomFilter;

$approximateItemCount = 100000;
$falsePositiveProbability = 0.001;

// Creates a bloom filter with a backing bit array that's about 1.4 million
// bits, or about 180KB in size.
$bf = BloomFilter::init($approximateItemCount, $falsePositiveProbability);

$bf->add('item1');
$bf->add('item2');
$bf->add('item3');

$bf->exists('item1'); // true
$bf->exists('item2'); // true
$bf->exists('item3'); // true

// The following call will return false with a 0.1% probability of
// being true as long as the amount of items in the filter are < 100000
$bf->exists('non-existing-item');

$serialized = json_encode($bf);
unset($bf);

$bf = BloomFilter::initFromJson(json_decode($serialized, true));
unset($serialized);

// The $bf variable is right back to where it was before serialization
```

## Requirements ##

The package.json file has the requirement for PHP >=5.6, however the code very
well may work in for PHP >=5.4, I just can't easily get the CI tools to work
for PHP 5.4 through 7.1 so I just left it at the currently supported version
of PHP.

## License ##

You can find the license for this code in [the LICENSE file](LICENSE).
