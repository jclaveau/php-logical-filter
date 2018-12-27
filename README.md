# php-logical-filter
This class provides a way to define complex filters freely and the tools to handle them easily.
This is a POK that opens a lot of questions on how to use them and how to refactor all its core. Meanwhile, the public api gets more and more robust!

## Quality
[![Build Status](https://travis-ci.org/jclaveau/php-logical-filter.png?branch=master)](https://travis-ci.org/jclaveau/php-logical-filter)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jclaveau/php-logical-filter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jclaveau/php-logical-filter/?branch=master)
[![codecov](https://codecov.io/gh/jclaveau/php-logical-filter/branch/master/graph/badge.svg)](https://codecov.io/gh/jclaveau/php-logical-filter)
[![contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg?style=flat)](https://github.com/jclaveau/php-logical-filter/issues)
[![Viewed](http://hits.dwyl.com/jclaveau/php-logical-filter.svg)](http://hits.dwyl.com/jclaveau/php-logical-filter)

## Installation

php-logical-filter will be available on composer once the public api will be stabilized by the implementation of True and False rules.
The 0.9.x version is available by cloning this repository:

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/jclaveau/php-logical-filter"
            "no-api": true
        }
    ],
    "require": {
        "jclaveau/php-logical-filter": "0.9.*",
    }
}
```

## Usage

All the usages are gathered in tests/functionnal but the really essential usage is probably this one:
```php
$array = [
    [
        'field_1' => 8,
        'field_2' => 3,
    ],
    [
        'field_1' => 12,
        'field_2' => 4,  // not matching field
    ],
];

$filtered_array = array_filter( $array, new LogicalFilter(
    ['field_2', '!=', 4]
));

// $filtered_array =>
//     [
//         [
//             'field_1' => 8,
//             'field_2' => 3,
//         ],
//     ],

```

A lot more to come :)


## Related

+ https://github.com/keboola/php-filter
+ https://github.com/hollodotme/FluidValidator (intersesting validators by type)
+ https://github.com/Respect/Validation (most popular?)
+ https://packagist.org/packages/kuria/options (same purpose?)
