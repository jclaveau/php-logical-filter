<?php
namespace JClaveau\LogicalFilter\Tests;

use JClaveau\LogicalFilter\LogicalFilter;
use JClaveau\Async\DeferredCallChain;

trait LogicalFilterTest_simplify_different_operands
{
    public function test_simplify_different_operands_dataProvider()
    {
        $cases = [];
        $cases["= vs <"] = [
            "with solution" => [
                'before' =>
                    ['and',
                        ['field_1', '=', 3],
                        ['field_1', '<', 4],
                    ],
                'after' => [
                    [], ['field_1', '=', 3]
                ]
            ],
            "without solution" => [
                'before' =>
                    ['and',
                        ['field_1', '=', 3],
                        ['field_1', '<', 2],
                    ],
                'after' => [
                    [], ['and']
                ]
            ],
            "same value" => [ // checks that the comparison is strict
                'before' =>
                    ['and',
                        ['field_1', '=', 3],
                        ['field_1', '<', 3],
                    ],
                'after' => [
                    [], ['and']
                ]
            ],
            "equal null" => [
                'before' =>
                    ['and',
                        ['field_1', '=', null],
                        ['field_1', '<', 3],
                    ],
                'after' => [
                    [], ['and']
                ]
            ],
        ];

        $cases["= vs >"] = [
            "with solution" => [
                'before' =>
                    ['and',
                        ['field_1', '=', 3],
                        ['field_1', '>', 2],
                    ],
                'after' => [
                    [], ['field_1', '=', 3]
                ]
            ],
            "without solution" => [
                'before' =>
                    ['and',
                        ['field_1', '=', 3],
                        ['field_1', '>', 4],
                    ],
                'after' => [
                    [], ['and']
                ]
            ],
            "same value" => [ // checks that the comparison is strict
                'before' =>
                    ['and',
                        ['field_1', '=', 3],
                        ['field_1', '>', 3],
                    ],
                'after' => [
                    [], ['and']
                ]
            ],
            "equal null" => [
                'before' =>
                    ['and',
                        ['field_1', '=', null],
                        ['field_1', '>', 3],
                    ],
                'after' => [
                    [], ['and']
                ]
            ],
        ];

        $cases["< vs >"] = [
            "with solution" => [
                'before' =>
                    ['and',
                        ['field_1', '<', 3],
                        ['field_1', '>', 2],
                    ],
                'after' => [
                    [],
                    ['and',
                        ['field_1', '<', 3],
                        ['field_1', '>', 2],
                    ],
                ]
            ],
            "without solution" => [
                'before' =>
                    ['and',
                        ['field_1', '<', 3],
                        ['field_1', '>', 4],
                    ],
                'after' => [
                    [], ['and']
                ]
            ],
            "same value" => [
                'before' =>
                    ['and',
                        ['field_1', '<', 3],
                        ['field_1', '>', 3],
                    ],
                'after' => [
                    [], ['and']
                ]
            ],
        ];


        $cases["!= vs ="] = [
            "simplifiable" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', 3],
                        ['field_1', '=', 4],
                    ],
                'after' => [
                    [],
                    ['field_1', '=', 4],
                ]
            ],
            "same value" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', 3],
                        ['field_1', '=', 3],
                    ],
                'after' => [
                    [],
                    ['and'],
                ],
            ],
            "not equal null simplifiable" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', null],
                        ['field_1', '=', 3],
                    ],
                'after' => [
                    [],
                    ['field_1', '=', 3],
                ]
            ],
            "not equal null compared to 0 simplifiable" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', null],
                        ['field_1', '=', 0],
                    ],
                'after' => [
                    [],
                    ['field_1', '=', 0],
                ]
            ],
            "not equal null without solution" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', null],
                        ['field_1', '=', null],
                    ],
                'after' => [
                    [],
                    ['and'],
                ]
            ],
            "not equal null with all solutions" => [
                'before' =>
                    ['or',
                        ['field_1', '!=', null],
                        ['field_1', '=', null],
                    ],
                'after' => [
                    [],
                    [
                        'or',
                        true,
                        // ['field_1', '=', null],
                        // ['field_1', '!=', null],
                    ],
                ],
                // TODO this filter should just value true as it doesn't filter
                // anything. @see https://github.com/jclaveau/php-logical-filter/issues/38
                // to replace the OrRule by a TrueRule
                'status' => (new DeferredCallChain)->markTestSkipped('Requires the support operands being TRUE or FALSE')
            ],
        ];

        $cases["!= vs <"] = [
            "not simplifiable" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', 3],
                        ['field_1', '<', 4],
                    ],
                'after' => [
                    [],
                    ['and',
                        ['field_1', '!=', 3],
                        ['field_1', '<', 4],
                    ],
                ]
            ],
            "simplifiable" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', 3],
                        ['field_1', '<', 2],
                    ],
                'after' => [
                    [],
                    ['field_1', '<', 2],
                ]
            ],
            "same value" => [ // checks that the comparison is strict
                'before' =>
                    ['and',
                        ['field_1', '!=', 3],
                        ['field_1', '<', 3],
                    ],
                'after' => [
                    [],
                    ['field_1', '<', 3],
                ],
            ],
            "equal null simplifiable" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', null],
                        ['field_1', '<', -1],
                    ],
                'after' => [
                    [],
                    ['field_1', '<', -1],
                ]
            ],
            "equal null not simplifiable" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', null],
                        ['field_1', '<', 3],
                    ],
                'after' => [
                    [],
                    ['and',
                        ['field_1', '!=', null],
                        ['field_1', '<', 3],
                    ],
                ],
                'status' => (new DeferredCallChain)->markTestIncomplete('TODO')
            ],
            "not equal null OR below not simplifiable" => [
                'before' =>
                    ['or',
                        ['field_1', '!=', null],
                        ['field_1', '<', 3],
                    ],
                'after' => [
                    [],
                    ['or',
                        ['field_1', '!=', null],
                        ['field_1', '<', 3],
                    ],
                ]
            ],
        ];


        $cases["!= vs >"] = [
            "not simplifiable" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', 3],
                        ['field_1', '>', 2],
                    ],
                'after' => [
                    [],
                    ['and',
                        ['field_1', '!=', 3],
                        ['field_1', '>', 2],
                    ],
                ]
            ],
            "simplifiable" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', 3],
                        ['field_1', '>', 4],
                    ],
                'after' => [
                    [],
                    ['field_1', '>', 4],
                ]
            ],
            "same value" => [ // checks that the comparison is strict
                'before' =>
                    ['and',
                        ['field_1', '!=', 3],
                        ['field_1', '>', 3],
                    ],
                'after' => [
                    [],
                    ['field_1', '>', 3],
                ]
            ],
            "not equal null simplifiable" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', null],
                        ['field_1', '>', 3],
                    ],
                'after' => [
                    [],
                    ['field_1', '>', 3],
                ]
            ],
            "not equal null not simplifiable" => [
                'before' =>
                    ['and',
                        ['field_1', '!=', null],
                        ['field_1', '>', -1],
                    ],
                'after' => [
                    [],
                    ['and',
                        ['field_1', '!=', null],
                        ['field_1', '>', -1],
                    ],
                ],
                'status' => (new DeferredCallChain)->markTestIncomplete('TODO')
            ],
            "not equal null OR above not simplifiable" => [
                'before' =>
                    ['or',
                        ['field_1', '!=', null],
                        ['field_1', '>', 3],
                    ],
                'after' => [
                    [],
                    ['or',
                        ['field_1', '!=', null],
                        ['field_1', '>', 3],
                    ],
                ]
            ],
        ];

        $cases["= vs in"] = [
            "with solution" => [
                'before' =>
                    ["and",
                        ["type", "=",  "a"],
                        ["type", "in", ["a", "b", "c", "d", null]],
                    ],
                'after' => [
                    [],
                    ["type", "=",  "a"],
                ]
            ],
            "without solution" => [
                'before' =>
                    ["and",
                        ["type", "=",  "z"],
                        ["type", "in", ["a", "b", "c", "d", null]],
                    ],
                'after' => [
                    [], ['or']
                ]
            ],
            "equal null" => [
                'before' =>
                    ["and",
                        ["type", "=",  null],
                        ["type", "in", ["a", "b", "c", "d", null]],
                    ],
                'after' => [
                    [],
                    ["type", "=",  null],
                ]
            ],
        ];

        $cases["> vs in"] = [
            "with solution" => [
                'before' =>
                    ["and",
                        ["field2", ">", 10],
                        ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    ],
                'after' => [
                    [],
                    ["field2", "in", [11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                ]
            ],
            "without solution" => [
                'before' =>
                    ["and",
                        ["field2", ">", 21],
                        ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    ],
                'after' => [
                    // ['in.normalization_threshold' => 9]
                    [],
                    ['or'],
                ]
            ],
            "ordering null" => [
                'before' =>
                    ["and",
                        ["field2", ">", -10],
                        ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    ],
                'after' => [
                    [],
                    ["field2", "in", [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                ]
            ],
        ];

        $cases["< vs in"] = [
            "with solution" => [
                'before' =>
                    ["and",
                        ["field2", "<", 10],
                        ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    ],
                'after' => [
                    [],
                    ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9]],
                ]
            ],
            "without solution" => [
                'before' =>
                    ["and",
                        ["field2", "<", 0],
                        ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    ],
                'after' => [
                    // ['in.normalization_threshold' => 9]
                    [],
                    ['or'],
                ]
            ],
            "ordering null" => [
                'before' =>
                    ["and",
                        ["field2", "<", 30],
                        ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    ],
                'after' => [
                    [],
                    ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                ]
            ],
        ];

        $cases["!= vs in"] = [
            "simplifiable" => [
                'before' =>
                    ["and",
                        ["field2", "!=", 10],
                        ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    ],
                'after' => [
                    [],
                    ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    // ["or",
                        // ["and",
                            // ["field2", "!=", 10],
                            // ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                        // ],
                    // ],
                ],
                'status' => (new DeferredCallChain)->markTestIncomplete('TODO')
            ],
            "null simplifiable" => [
                'before' =>
                    ["and",
                        ["field2", "!=", null],
                        ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    ],
                'after' => [
                    [],
                    ["field2", "in", [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    // ["or",
                        // ["and",
                            // ["field2", "!=", null],
                            // ["field2", "in", [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                        // ],
                    // ],
                ],
                'status' => (new DeferredCallChain)->markTestIncomplete('TODO')
            ],
            "simplifiable with no change" => [
                'before' =>
                    ["and",
                        ["field2", "!=", -6],
                        ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    ],
                'after' => [
                    [],
                    ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                ],
                'status' => (new DeferredCallChain)->markTestIncomplete('TODO')
            ],

            "ordering null" => [
                'before' =>
                    ["and",
                        ["field2", "!=", 30],
                        ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    ],
                'after' => [
                    [],
                    ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                    // ["or",
                        // ["and",
                            // ["field2", "!=", 30],
                            // ["field2", "in", [null, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                        // ],
                    // ],
                ],
                'status' => (new DeferredCallChain)->markTestIncomplete('TODO')
            ],
        ];

        $cases["= vs !in"] = [
            "with solution" => [
                'before' =>
                    ["and",
                        ["type", "=",  "a"],
                        ["type", "!in", ["b", "c", "d", null]],
                    ],
                'after' => [
                    [],
                    ["type", "=",  "a"],
                ]
            ],
            "without solution" => [
                'before' =>
                    ["and",
                        ["type", "=",  "a"],
                        ["type", "!in", ["a", "b", "c", "d", null]],
                    ],
                'after' => [
                    [],
                    ['and'],
                ]
            ],
            "equal null with solution" => [
                'before' =>
                    ["and",
                        ["type", "=",  null],
                        ["type", "!in", ["a", "b", "c", "d"]],
                    ],
                'after' => [
                    [],
                    ["type", "=",  null],
                ]
            ],
            "equal null without solution" => [
                'before' =>
                    ["and",
                        ["type", "=",  null],
                        ["type", "!in", ["a", "b", "c", "d", null]],
                    ],
                'after' => [
                    [],
                    ['and'],
                ]
            ],
        ];

        $cases["!= vs !in"] = [
            "with solution" => [
                'before' =>
                    ["and",
                        ["type", "!=",  "a"],
                        ["type", "!in", ["a", "b", "c", "d"]],
                    ],
                'after' => [
                    [
                        'in.normalization_threshold' => 4,
                        'not_equal.normalization'    => true,
                    ],
                    ["or",
                        ["type", ">",  "d"],
                        ["type", "<",  "a"],
                        ["and",
                            ["type", ">",  "c"],
                            ["type", "<",  "d"],
                        ],
                        ["and",
                            ["type", ">",  "b"],
                            ["type", "<",  "c"],
                        ],
                        ["and",
                            ["type", ">",  "a"],
                            ["type", "<",  "b"],
                        ],
                    ],
                ]
            ],
            "with solution" => [
                'before' =>
                ["and",
                    ["type", "!=",  "z"],
                    ["type", "!in", ["a", "b", "c", "d"]],
                ],
                'after' => [
                    [
                        'in.normalization_threshold' => 5,
                        'not_equal.normalization'    => true,
                    ],
                    ["or",
                        ["type", ">",  "z"],
                        // ["type", ">",  "d"],
                        ["type", "<",  "a"],
                        ["and",
                            ["type", ">",  "d"],
                            ["type", "<",  "z"],
                        ],
                        ["and",
                            ["type", ">",  "c"],
                            ["type", "<",  "d"],
                        ],
                        ["and",
                            ["type", ">",  "b"],
                            ["type", "<",  "c"],
                        ],
                        ["and",
                            ["type", ">",  "a"],
                            ["type", "<",  "b"],
                        ],
                    ],
                ]
            ],
        ];

        $cases["in vs !in"] = [
            "with solution" => [
                'before' =>
                    ["and",
                        ["field", "in", [1, 2, 3, 4]],
                        ["field", "!in", [3, 4, 5, 6]],
                    ],
                'after' => [
                    [],
                    ["field", "in", [1, 2]],
                ]
            ],
        ];

        $named_cases = [];
        foreach ($cases as $vs) {
            foreach ($vs as $case) {
                $name =
                    $case['before'][1][1].' '.var_export($case['before'][1][2], true)
                    .' vs '.
                    $case['before'][2][1].' '.var_export($case['before'][2][2], true)
                    ;

                $named_cases[$name] = $case;
            }
        }

        return $named_cases;
    }


    /**
     * @dataProvider test_simplify_different_operands_dataProvider
     */
    public function test_simplify_different_operands($input, $results, $status=null)
    {
        if ($status instanceof DeferredCallChain) {
            $status($this);
        }
        elseif ($status != null) {
            throw new \InvalidArgumentException(
                "unhandled test status"
            );
        }

        $filter = LogicalFilter::new_($input)
        // ->dump(true)
        ;

        $this->assertEquals(
            $results[1],
            $filter
                ->simplify($results[0])
                // ->dump(true)
                ->toArray()
        );

        $this->assertEquals(
            ! in_array($results[1], [['or'], ['and'], false]),
            $filter
                ->hasSolution($results[0])
        );
    }

    /**
     * /
    public function test_hasSolution()
    {
        $this->assertTrue(
            (new LogicalFilter(
            ['or',
                ['and',
                    ['field_5', '>', 'a'],
                    ['field_5', '<', 'a'],
                ],
                ['field_6', '=', 'b'],
            ]))
            ->hasSolution()
        );
    }

    /**
     * /
    public function test_simplify_differentOperands_in_above_below()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["or",
                    ["field2", "=", 4],
                    // ["field2", "<", 3],
                ],
                ["field2", "in", [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
            ],
            null,
            ['in.normalization_threshold' => 9]
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ["or",
                ["field2", "=", 4],
                ["field2", "in", [11, 12, 13, 14, 15, 16, 17, 18, 19, 20]],
                ["field2", "=", 1],
                ["field2", "=", 2],
            ],
            $filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     * /
    public function test_simplify_equal_rules_with_fields_having_different_types()
    {
        // This produced a bug due to comparisons between different fields
        // and missing unset
        $filter = (new LogicalFilter(
            ['and',
                ['A_type', '=', 'INTERNE'],
                ['A_id', '>', 0],
                ['A_id', '>', 12],
                ['A_id', '>=', 100],
                ['A_id', '<=', 100],
                ['A_id', '<', 2000],
                ['A_id', '=', 100],
                ['date', '=', \DateTime::__set_state(array(
                   'date' => '2018-07-04 00:00:00.000000',
                   'timezone_type' => 3,
                   'timezone' => 'UTC',
                ))],
                ['E', '=', 'impression'],
            ]
        ))
        // ->dump(true)
        ->simplify([
        ])
        // ->dump(true)
        ;

        $this->assertEquals(
            ['and',
                ['A_type', '=', 'INTERNE'],
                ['A_id', '=', 100],
                ['date', '=', new \DateTime('2018-07-04')],
                ['E', '=', 'impression'],
            ],
            $filter
                // ->dump(!true)
                ->toArray()
        );
    }

    /**/
}
