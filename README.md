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



## Basic Usage
All the usages are gathered in the tests: https://github.com/jclaveau/php-logical-filter/tree/master/tests
Here is the basic usage to get started.


### Rules Syntax

The example below lists the supported syntaxes of rule descriptions (some updates
would be found in the test_toString() test).

```php
// Via the constructor
$filter = (new LogicalFilter(
    ['and',
        ['or',
            ['field_1', '=', 3],
            ['field_1', '!=', 100],
            ['field_1', '>', 20],
        ],
        ['not',
            ['and',
                ['field_2', '<', -5],
                ['field_2', '>', -11],
            ],
        ],
        ['field_1', 'regexp', "/^prefix-[^-]+-suffix$/"],   // PCRE regexp
        ['field_3', 'in', [2, null]],
        ['field_4', '!in', [4, 12]],
        ['field_5', '<=', 3],
        ['field_5', '>=', 12],
        ['field_6', '><', [20, 30]],                        // exclusive between
        ['field_6', '=><', [20, 30]],                       // left inclusive between
        ['field_6', '=><=', [20, 30]],                      // fully inclusive between like MySQL "BETWEEN" https://dev.mysql.com/doc/refman/5.5/en/comparison-operators.html#operator_between
        ['field_6', '><=', [20, 30]],                       // right inclusive between
        ['date', '>', new \DateTime("2018-07-19")],
        [key(), '=', 3],                                    // rule applied on the key of an entry
        [value()->lazyMethodCall(), '=', 3],                // rule applied on the result of the call of lazyMethodCall() on the value
    ]
));

// Later
$filter->and_(['another_field', '>', 45]]);
$filter->or_(['another_field', '<', 12]]);

// Filters as rules or insie a rule description
$filter2 = new LogicalFilter(['filter2_field', '=', 'whatever']);
$filter->and_(
    ['or',
        ['whatever', '<', 'zzz'],
        $filter2,
    ]
);
```

### Applying the filter on values or arrays of values

```php
// On an array of values
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

// Basic way
$filtered_array = (new LogicalFilter(
    ['field_2', '!=', 4]
))
->applyOn($array);

// applyOn() works on any Traversable
$filtered_array = (new LogicalFilter(
    ['field_2', '!=', 4]
))
->applyOn(new Collection([['field_2' => 3], ['field_2' => 4]]));

// Native way
$filtered_array = array_filter($array, new LogicalFilter(
    ['field_2', '!=', 4]
));

// Would result in
// $filtered_array =>
//     [
//         [
//             'field_1' => 8,
//             'field_2' => 3,
//         ],
//     ],

// On a single value
$validating_cases = (new LogicalFilter(
    ['field_2', '!=', 4]
))
->validates(['field_1' => 'lala', 'field_2' => 3]);

// => $validating_cases == ['field_2', '!=', 4]

// As a function
$validating_cases = (new LogicalFilter(
    ['field_2', '!=', 4]
))(['field_1' => 'lala', 'field_2' => 3]);
```

### Conversions
Filters can be converted to MySQL "WHERE" clause or ElasticSearch filters glad to Converters.
Some basic examples are located in https://github.com/jclaveau/php-logical-filter/tree/master/src/Converter

```php
// Mysql Example
$filter = (new LogicalFilter(
    ['and',
        ['field_1', '=', 2],
        ['or',
            ['field_2', '>', 4],
            ['field_2', '<', -4],
        ],
        ['field_3', '=', null],
        ['field_4', '!=', null],
        ['field_5', 'regexp', "/^(ab)+/i"],
        ['field_6', 'in', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21]],
        ['field_7', '!in', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21]],
        ['field_8', '=', new \DateTime('2018-11-12')],
        ['field_9', '>=', 4],
        ['field_10', '<=', -4],
    ]
));

$inline_sql = (new InlineSqlMinimalConverter())->convert($filter);

// $inline_sql['sql'] == "(field_1 = 2 AND field_2 > 4 AND field_3 IS NULL AND field_4 IS NOT NULL AND field_5 REGEXP :param_b30f6679 AND field_6 IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21) AND field_7 NOT IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21) AND field_8 = '2018-11-12 00:00:00' AND field_9 >= 4 AND field_10 <= -4) OR (field_1 = 2 AND field_2 < -4 AND field_3 IS NULL AND field_4 IS NOT NULL AND field_5 REGEXP :param_b30f6679 AND field_6 IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21) AND field_7 NOT IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21) AND field_8 = '2018-11-12 00:00:00' AND field_9 >= 4 AND field_10 <= -4)",
// $inline_sql['parameters'] == ['param_b30f6679' => '(?i)^(ab)+']
```




## Advanced Usage

### Simplification in cases

LogilFilter supports a simplification strategy that removes rules duplicates:

```php
$filter = (new LogicalFilter(
    ['and',
        ['field_1', '>', 5],
        ['field_1', '>', 12],
        ['field_1', '>=', 8],
    ]
))
->simplify()
->dump();

// => ['field_1', '>', 12]

```

It can also remove inconsistent "cases":
```php
$filter = (new LogicalFilter(
    ['and',
        ['or',
            ['field_1', '>', 5],
            ['field_1', '>', 12],
        ]
        ['field_1', '<', 7],
    ]
))
->simplify()
->dump();

// => ['and',
//        ['field_1', '>', 5],
//        ['field_1', '<', 7],
//    ]
```

To do so, the filter parses it's "rule tree" to merge all "or" rule
operations into one at the root of the tree. For example:
```php
$filter = (new LogicalFilter(
    ['and',
        ['or',
            ['field_1', '>', 5],
            ['field_1', '>', 12],
        ]
        ['field_2', '=', 3],
    ]
))
->simplify()
->dump();

// ['or',                           // one "or" rule at the root of the tree
//     ['and',                      // case 1
//         ['field_1', '>', 5],
//         ['field_2', '=', 3],
//     ],
//     ['and',                      // case 2
//         ['field_1', '>', 12],
//         ['field_2', '=', 3],
//     ],
// ],
```

"Cases" can then be defined as a set of leaf rules (non operation rules)
gathered as operands of an "And rule". Cases are themselves gathered in
an "or rule" at the root of the rule tree.

If in a case, some rules are in conflict like here in the second one:
```php
['or',                           // one "or" rule at the root of the tree
    ['and',                      // case 1
        ['field_1', '>', 5],
        ['field_2', '=', 3],
    ],
    ['and',                      // case 2: inconsistent
        ['field_1', '>', 12],
        ['field_1', '<', 3],
    ],
],
```
We can simply remove it:
```php
['or',                           // one "or" rule at the root of the tree
    ['and',                      // case 1
        ['field_1', '>', 5],
        ['field_2', '=', 3],
    ],
],
```

### Solution checking

Now we have cases, we can go further and deduce semothing obvious: If there is
no possible case once a LogicalFilter is simplified, this means that this filter
cannot match any solution:

```php
$filter = (new LogicalFilter(
    ['and',
        ['or',
            ['field_1', '>', 5],
            ['field_1', '>', 12],
        ]
        ['field_2', '<', 4],
    ]
))
->simplify()
->dump();

// => ['or']
// This is an operation with no operand so no possible case so no solution.
```

The hasSolution() is implemented to handle this:
```php
(new LogicalFilter(
    ['and',
        ['or',
            ['field_1', '>', 5],
            ['field_1', '>', 12],
        ]
        ['field_2', '<', 4],
    ]
))
->hasSolution();

// => false
// hasSolution calls simplify fisrt and returns true if at least one
// possible case exists, false otherwize.
```

Also hasSolutionIf() can be useful too:
```php
(new LogicalFilter(
    ['and',
        ['or',
            ['field_1', '>', 5],
            ['field_1', '>', 12],
        ]
        ['field_2', '<', 4],
    ]
))
->hasSolutionIf(['field_2', '>', 5]);

// => false
```



### Meta-Language

LogicalFilter provide a toString() (bound to __toString()) method that
will return a string containing the rule description you would have
written in PHP to generate the same filter.

This feature is really useful for unit test writing but could also lead
to interesting experiments on reflexiv logical filters.

```php
$filter = (new LogicalFilter(
    ['and',
        ['or',
            ['field_1', '>', 5],
            ['field_1', '>', 12],
        ]
        ['field_2', '=', 3],
    ]
));

$filter->toString() == (new LogicalFilter($filter->toString()))->toString();
// => true
```


### To come
 + Filtering rules of filters
 + getRange / forEach case / getSemanticId()
 + ...


## Related

### Interesting implementations
+ https://github.com/keboola/php-filter
+ https://github.com/hollodotme/FluidValidator (intersesting validators by type)
+ https://github.com/Respect/Validation (most popular?)
+ https://packagist.org/packages/kuria/options (same purpose?)

### Theory
+ https://en.wikipedia.org/wiki/Logical_disjunction
+ https://en.wikipedia.org/wiki/First-order_logic
+ https://en.wikipedia.org/wiki/Higher-order_logic
+ https://en.wikipedia.org/wiki/Model_theory
