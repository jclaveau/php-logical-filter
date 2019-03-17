<?php
namespace JClaveau\LogicalFilter\Filterer;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class RuleFilteringTest extends \AbstractTest
{
    /**
     */
    public function test_equal()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
                ['field_2', '=', 2],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '=', 2]
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field',    '=', 'field_1'],
                        ['operator', 'in', ['=', 'and']],
                        ['value',    '=', 2],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_equal_null()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', null],
                ['field_1', '=', 2],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '=', null],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field',    '=', 'field_1'],
                        ['operator', 'in', ['=', 'and']],
                        ['value',    '=', null],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_not_equal()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '>', 2],
                ['field_1', '=', 2],
                ['field_1', '<', 3],
                ['field_2', '=', 2],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '=', 2],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field',    '!=', 'field_2'],
                        ['operator', '!=', '>'],
                        ['value',    '!=', 3],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_not_equal_null()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '>', 2],
                ['field_2', '=', null],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '>', 2],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['value',    '!=', null],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_above()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
                ['field_2', '=', 1],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '=', 2],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field', '>', 'field_'],
                        ['value', '>', 1],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_below()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
                ['field_2', '=', 1],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '=', 2],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field', '<', 'field_2'],
                        ['value', '<', 3],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_above_or_equal()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
                ['field_2', '=', 1],
                ['field_3', '=', 0],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '=', 2],
                ['field_2', '=', 1],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field', '>=', 'field_1'],
                        ['value', '>=', 1],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_below_or_equal()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 0],
                ['field_2', '=', 1],
                ['field_3', '=', 2],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '=', 0],
                ['field_2', '=', 1],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field', '<=', 'field_3'],
                        ['value', '<=', 1],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_between()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 0],
                ['field_2', '=', 1],
                ['field_3', '=', 8],
                ['field_4', '=', 2],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_2', '=', 1],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field', '><', ['field_1', 'field_4']],
                        ['value', '><', [-1, 4]],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_between_or_equal_lower()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 0],
                ['field_2', '=', 1],
                ['field_3', '=', 8],
                ['field_4', '=', 2],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '=', 0],
                ['field_2', '=', 1],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field', '=><', ['field_1', 'field_4']],
                        ['value', '=><', [0, 4]],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_between_or_equal_upper()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 0],
                ['field_2', '=', 1],
                ['field_3', '=', 8],
                ['field_4', '=', 4],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_2', '=', 1],
                ['field_4', '=', 4],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field', '><=', ['field_1', 'field_4']],
                        ['value', '><=', [0, 4]],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_between_or_equal_both()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 0],
                ['field_2', '=', 1],
                ['field_3', '=', 8],
                ['field_4', '=', 4],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '=', 0],
                ['field_2', '=', 1],
                ['field_4', '=', 4],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field', '=><=', ['field_1', 'field_4']],
                        ['value', '=><=', [0, 4]],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_regexp()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1',  '=', 'aa'],
                ['field_11', '=', 'aa'],
                ['field_1',  '>', 'aa'],
                ['field_1',  '=', '_èà*'],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1',  '=', 'aa'],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field',    'regexp', '/^field_\d$/'],
                        ['operator', 'regexp', '/^and|=$/'],
                        ['value',    'regexp', '/^\w+$/'],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_regexp_invalid()
    {
        $filter_to_filter = new LogicalFilter(
            ['field_1',  '=', 'aa']
        );

        try {
            $filter_to_filter
                ->filterRules(
                    ['field', 'regexp', 'not a valid regexp']
                )
                ;
            $this->assertTrue(false, "An error must have been thrown here");
        }
        catch (\InvalidArgumentException $e) {
            $this->assertEquals(
                "PCRE error 'preg_match(): Delimiter must not be alphanumeric or backslash' while applying the regexp 'not a valid regexp' to 'field_1'",
                $e->getMessage()
            );
        }
    }

    /**
     */
    public function test_in()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1',  '=', 'aa'],
                ['field_1',  '>', 'bb'],
                ['field_2',  '><', ['aa', 'bb']],
                ['field_2',  '=', 'lalalala'],
                ['field_3',  '>', 'aa'],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1',  '=', 'aa'],
                ['field_1',  '>', 'bb'],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field',    'in', ['field_1', 'field_2']],
                        ['operator', 'in', ['=', '>', 'and']],
                        ['value',    'in', ['aa', 'bb']],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_not_in()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1',  '=', 'aa'],
                ['field_1',  '>', 'bb'],
                ['field_2',  '><', ['aa', 'bb']],
                ['field_2',  '<', 4],
                ['field_2',  '>', 3],
                ['field_3',  '>', 'aa'],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_2',  '>', 3],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field',    '!in', ['field_1', 'field_3']],
                        ['operator', '!in', ['=', '><']],
                        ['value',    '!in', ['aa', 4]],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_by_description()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 'aa'],
                ['field_1', '>', 'bb'],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1',  '>', 'bb'],
            ],
            $filter_to_filter
                ->filterRules(
                    ['or',
                        ['operator', '=', 'and'],
                        ['description', '=', ['field_1',  '>', 'bb']],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_operations()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
                ['or',
                    ['field_2', '>', 4],
                    ['field_3', '<', 'aa'],
                ],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '=', 2],
                ['or'],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field',    '=', 'field_1'],
                        ['operator', 'in', ['=', 'and', 'or']],
                        ['value',    '=', 2],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_negations()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
                ['or',
                    ['not', ['field_1', '>', 4]],
                    ['field_3', '<', 'aa'],
                ],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '=', 2],
                ['or',
                    ['not', ['field_1', '>', 4]],
                ],
            ],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field',    '=', 'field_1'],
                        ['operator', 'in', ['=', '>', 'and', 'or', 'not']],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_no_solution()
    {
        $filter_to_filter = new LogicalFilter(
            ['field_1', '=', 0]
        );

        $this->assertEquals(
            ['and'],
            $filter_to_filter
                ->filterRules(
                    ['and',
                        ['field', '>', 'field_3'],
                    ]
                )
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_RuleFilter_with_rule_on_unhandled_property()
    {
        $filter_to_filter = new LogicalFilter(
            ['and',
                ['field_1', '=', 2],
            ]
        );

        try {
            $filter_to_filter
                ->filterRules(
                    ['or',
                        ['unhandled_property', '=', ['field_7', '=', 'plop']],
                    ]
                )
                ;

            $this->assertTrue(false, "An error must have been thrown here");
        }
        catch (\Exception $e) {
            $this->assertEquals(
                "Rule filters must belong to ["
                . implode(', ', ['field', 'operator', 'value', 'depth', 'description', 'children', 'path'])
                ."] contrary to : 'unhandled_property'",
                $e->getMessage()
            );
        }
    }

    /**/
}
