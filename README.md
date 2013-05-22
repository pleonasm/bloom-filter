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

Install via [Composer](http://getcomposer.org) (make sure you have composer in your path or in your project).

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

Not done coding yet...

## License ##

You can find the license for this code in [the LICENSE file](LICENSE).
