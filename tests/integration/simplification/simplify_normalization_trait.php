<?php
namespace JClaveau\LogicalFilter\Tests;

use JClaveau\LogicalFilter\LogicalFilter;
use JClaveau\LogicalFilter\Rule\InRule;

trait LogicalFilterTest_simplify_normalization
{
    /**
     */
    public function test_normalize_not_in()
    {
        /* simple case */
        $filter = (new LogicalFilter(
            ['and',
                ['field_3', '!in', [7, 11, 13]],
            ]
        ))
        ;

        $this->assertEquals(
            ['and',
                ['field_3', '!=', 7],
                ['field_3', '!=', 11],
                ['field_3', '!=', 13],
            ],
            $filter
                ->simplify([
                    'in.normalization_threshold' => 4,
                ])
                // ->dump(true)
                ->toArray()
        );

        /* with recursions */
        $filter = (new LogicalFilter(
            ['or',
                ['field_1', '<', 3],
                ['field_3', '!in', [7, 11, 13]],
                ['and',
                    ['field_4', '>', 2],
                    ['field_5', '!in', ['a', 'b', 'c']],
                ],
                ['and',
                    ['field_4', '=', 2],
                    ['field_5', '!in', ['a', 'b', 'c']],
                ],
                ['field_2', '<', 3],
                ['field_2', '=', 3],
            ]
        ));

        $this->assertEquals(
            ['or',
                ['field_1', '<', 3],
                ['and',
                    ['field_3', '!=', 7],
                    ['field_3', '!=', 11],
                    ['field_3', '!=', 13],
                ],
                ['and',
                    ['field_4', '>', 2],
                    ['field_5', '!=', 'a'],
                    ['field_5', '!=', 'b'],
                    ['field_5', '!=', 'c'],
                ],
                ['and',
                    ['field_4', '=', 2],
                    ['field_5', '!=', 'a'],
                    ['field_5', '!=', 'b'],
                    ['field_5', '!=', 'c'],
                ],
                ['field_2', '<', 3],
                ['field_2', '=', 3],
            ],
            $filter
                ->simplify([
                    'in.normalization_threshold' => 4,
                ])
                // ->dump(true)
                ->toArray()
        );

        // with normalization of equal rules also
        $filter = (new LogicalFilter(
            ['or',
                ['field_12', 'in', [7, 11, 13]],
                ['field_1', '<', 3],
                ['field_3', '!in', [7, 11, 13]],
                ['and',
                    ['field_4', '>', 2],
                    ['field_5', '!in', ['a', 'b', 'c']],
                ],
                ['and',
                    ['field_4', '=', 2],
                    ['field_5', '!in', ['a', 'b', 'c']],
                ],
                ['field_2', '<', 3],
                ['field_2', '=', 3],
            ]
        ));

        $this->assertEquals(
            ['or',
                ['field_1', '<', 3],
                ['and',
                    ['field_3', '!=', 7],
                    ['field_3', '!=', 11],
                    ['field_3', '!=', 13],
                ],
                ['and',
                    ['field_4', '>', 2],
                    ['field_5', '!=', 'a'],
                    ['field_5', '!=', 'b'],
                    ['field_5', '!=', 'c'],
                ],
                ['and',
                    ['field_4', '=', 2],
                    ['field_5', '!=', 'a'],
                    ['field_5', '!=', 'b'],
                    ['field_5', '!=', 'c'],
                ],
                ['field_2', '<', 3],
                ['field_2', '=', 3],
                ['field_12', '=', 7],
                ['field_12', '=', 11],
                ['field_12', '=', 13],
            ],
            $filter
                ->simplify([
                    // 'not_equal.normalization'    => true,
                    'in.normalization_threshold' => 4,
                ])
                // ->dump(true)
                ->toArray()
        );

        // with normalization of equal rules also
        $filter = (new LogicalFilter(
            ['or',
                ['field_1', '<', 3],
                ['field_3', '!in', [7, 11, 13]],
                ['and',
                    ['field_4', '>', 2],
                    ['field_5', '!in', ['a', 'b', 'c']],
                ],
                ['and',
                    ['field_4', '=', 2],
                    ['field_5', '!in', ['a', 'b', 'c']],
                ],
                ['field_2', '<', 3],
                ['field_2', '=', 3],
            ]
        ));

        $this->assertEquals(
            ['or',
                ['field_1', '<', 3],
                ['field_3', '>', 13],
                ['field_3', '<', 7],
                ['and',
                    ['field_3', '>', 11],
                    ['field_3', '<', 13],
                ],
                ['and',
                    ['field_3', '>', 7],
                    ['field_3', '<', 11],
                ],
                ['and',
                    ['field_4', '>', 2],
                    ['field_5', '>', 'c'],
                ],
                ['and',
                    ['field_4', '>', 2],
                    ['field_5', '>', 'b'],
                    ['field_5', '<', 'c'],
                ],
                ['and',
                    ['field_4', '>', 2],
                    ['field_5', '>', 'a'],
                    ['field_5', '<', 'b'],
                ],
                ['and',
                    ['field_4', '>', 2],
                    ['field_5', '<', 'a'],
                ],
                ['and',
                    ['field_4', '=', 2],
                    ['field_5', '>', 'c'],
                ],
                ['and',
                    ['field_4', '=', 2],
                    ['field_5', '>', 'b'],
                    ['field_5', '<', 'c'],
                ],
                ['and',
                    ['field_4', '=', 2],
                    ['field_5', '>', 'a'],
                    ['field_5', '<', 'b'],
                ],
                ['and',
                    ['field_4', '=', 2],
                    ['field_5', '<', 'a'],
                ],
                ['field_2', '<', 3],
                ['field_2', '=', 3],
            ],
            $filter
                ->simplify([
                    'not_equal.normalization'    => true,
                    'in.normalization_threshold' => 4,
                ])
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_normalize_not_equal()
    {
        $filter = new LogicalFilter(
            ['field_1', '!=', 'a']
        );

        $this->assertEquals(
            ['field_1', '!=', 'a'],
            $filter
                ->simplify()
                ->toArray()
        );

        $this->assertEquals(
            ['or',
                ['field_1', '>', 'a'],
                ['field_1', '<', 'a'],
            ],
            $filter
                ->simplify(['not_equal.normalization' => true])
                // ->dump()
                ->toArray()
        );

        // $this->assertEquals(
            // ['and',
                // ['field_1', '!=', 2],
                // ['field_1', '!=', 3],
            // ],
            // $filter
                // ->simplify(['not_in.normalization' => true])
                // ->dump(true)
                // ->toArray()
        // );

        // $this->assertEquals(
            // ['or',
                // ['field_1', '>', 3],
                // ['field_1', '<', 2],
                // ['and',
                    // ['field_1', '>', 2],
                    // ['field_1', '<', 3],
                // ],
            // ],
            // $filter
                // ->simplify(['not_equal.normalization' => true])
                // ->dump(true)
                // ->toArray()
        // );

    }

    /**
     */
    public function test_normalize_below_or_equal()
    {
        $filter = new LogicalFilter(
            ['field_1', '<=', 2]
        );

        $this->assertEquals(
            ['field_1', '<=', 2],
            $filter->simplify()->toArray()
        );

        $this->assertEquals(
            ['or',
                ['field_1', '<', 2],
                ['field_1', '=', 2],
            ],
            $filter
                ->simplify(['below_or_equal.normalization' => true])
                ->toArray()
        );
    }

    /**
     */
    public function test_normalize_above_or_equal()
    {
        $filter = new LogicalFilter(
            ['field_1', '>=', 2]
        );

        $this->assertEquals(
            ['field_1', '>=', 2],
            $filter
                ->simplify()
                ->toArray()
        );

        $this->assertEquals(
            ['or',
                ['field_1', '>', 2],
                ['field_1', '=', 2],
            ],
            $filter
                ->simplify(['above_or_equal.normalization' => true])
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_normalize_in_default_threshold_reached()
    {
        $filter = new LogicalFilter(['field', 'in', [
            0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27,
        ]]);

        $this->assertEquals(
            ['field', 'in', [
                0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27,
            ]],
            $filter
                ->simplify()
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_normalize_in_custom_threshold_by_simplification()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["type", "in", ["lolo","lala","lili"]],
                ["type", "in", ["lolo","lala","lili","lulu"]],
                ["user", "in", [10,23,27,28,30,32,56,60,61,62,65,74,76,77,86,98,99,104,110,111,116,118,123,130,134,135,142,144,146,147,148,149,150,151,154,157,159,163,170,174,178,179,188,198,200,209,210,211,212,213,222,235,236,243,244,245,246,259,262,266,268,270,271,272,273]],
            ]
        ));

        $this->assertEquals(
            ["or",
                ['and',
                    ["type", "=", "lolo"],
                    ["user", "in", [10,23,27,28,30,32,56,60,61,62,65,74,76,77,86,98,99,104,110,111,116,118,123,130,134,135,142,144,146,147,148,149,150,151,154,157,159,163,170,174,178,179,188,198,200,209,210,211,212,213,222,235,236,243,244,245,246,259,262,266,268,270,271,272,273]],
                ],
                ['and',
                    ["type", "=", "lala"],
                    ["user", "in", [10,23,27,28,30,32,56,60,61,62,65,74,76,77,86,98,99,104,110,111,116,118,123,130,134,135,142,144,146,147,148,149,150,151,154,157,159,163,170,174,178,179,188,198,200,209,210,211,212,213,222,235,236,243,244,245,246,259,262,266,268,270,271,272,273]],
                ],
                ['and',
                    ["type", "=", "lili"],
                    ["user", "in", [10,23,27,28,30,32,56,60,61,62,65,74,76,77,86,98,99,104,110,111,116,118,123,130,134,135,142,144,146,147,148,149,150,151,154,157,159,163,170,174,178,179,188,198,200,209,210,211,212,213,222,235,236,243,244,245,246,259,262,266,268,270,271,272,273]],
                ],
            ],
            $filter
                ->simplify([
                    'in.normalization_threshold' => 5
                ])
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_normalize_in_rules_changing_simplification_threshold()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["field", "in", [1, 2, 3, 4, 5, 6, 7]],
                ["field_2", "in", [1, 2, 3, 4, 5, 6, 7, 8]],
            ],
            null,
            ['in.normalization_threshold' => 5]
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ["or",
                ["and",
                    ["field", "in", [1, 2, 3, 4, 5, 6, 7]],
                    ["field_2", "in", [1, 2, 3, 4, 5, 6, 7, 8]],
                ],
            ],
            $filter
                // ->dump(true)
                ->toArray()
        );

        $filter->onEachRule(
            ['and',
                ['operator', '=', 'in'],
                ['field', '=', 'field_2'],
            ],
            function (InRule $rule, $key, array &$siblings) {
                $rule->setOptions(['in.normalization_threshold' => 10]);
            }
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ["field", "in", [1, 2, 3, 4, 5, 6, 7]],
                    ['field_2', '=', 1],
                ],
                ['and',
                    ["field", "in", [1, 2, 3, 4, 5, 6, 7]],
                    ['field_2', '=', 2],
                ],
                ['and',
                    ["field", "in", [1, 2, 3, 4, 5, 6, 7]],
                    ['field_2', '=', 3],
                ],
                ['and',
                    ["field", "in", [1, 2, 3, 4, 5, 6, 7]],
                    ['field_2', '=', 4],
                ],
                ['and',
                    ["field", "in", [1, 2, 3, 4, 5, 6, 7]],
                    ['field_2', '=', 5],
                ],
                ['and',
                    ["field", "in", [1, 2, 3, 4, 5, 6, 7]],
                    ['field_2', '=', 6],
                ],
                ['and',
                    ["field", "in", [1, 2, 3, 4, 5, 6, 7]],
                    ['field_2', '=', 7],
                ],
                ['and',
                    ["field", "in", [1, 2, 3, 4, 5, 6, 7]],
                    ['field_2', '=', 8],
                ],
            ],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_normalize_in_custom_threshold_by_filter_being_null()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["field", "in", [1, 2, 3, 4]],
            ],
            null,
            ['inrule.simplification_threshold' => 0]
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ["field", "in", [1, 2, 3, 4]],
            $filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_normalize_in_as_equal_always()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["field", "in", ["PLOP", "PROUT"]],
                ["field", "in", ["PLOUF", "PROUT"]],
                ["field", "in", ["PROUT", "PLOUF", "POUET"]],
            ]
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ['field', '=', 'PROUT'],  // An in rule with only one possibility is always an equal one
            $filter
                // ->dump(true)
                ->toArray()
        );
    }


    /**
     */
    public function test_normalize_in_custom_threshold_by_filter()
    {
        $filter = new LogicalFilter(
            ['field_1', 'in', ['a', 'b', 'c']],
            null,
            ['inrule.simplification_threshold' => 20]
        );

        $filter->getRules(false)->addPossibilities(['d', 'e']);

        $this->assertEquals(
            ['a', 'b', 'c', 'd', 'e'],
            $filter->getRules()->getPossibilities()
        );

        $this->assertEquals(
            [
                'or',
                ['field_1', '=', 'a'],
                ['field_1', '=', 'b'],
                ['field_1', '=', 'c'],
                ['field_1', '=', 'd'],
                ['field_1', '=', 'e'],
            ],
            $filter
                // ->dump(!true)
                ->simplify([
                    // 'stop_after' =>
                    // AbstractOperationRule::remove_negations,
                    // AbstractOperationRule::rootify_disjunctions,
                    // AbstractOperationRule::unify_atomic_operands,
                    // AbstractOperationRule::remove_invalid_branches,
                    'in.normalization_threshold' => 6
                ])
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_normalize_combined()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["A_type", "in", [
                    "INTERNE",
                    "PROD",
                ]],
                ["A_id", "!in", [
                    0,
                ]],
                ["A_type", "in", [
                    "DIFF",
                    "INTERNE",
                ]],
                ["A_id", "!in", [
                    0,
                ]],
                ["A_id", "!in", [
                    100,
                    101,
                ]],
                ["A_id", "!in", [
                    100921,
                    100923,
                ]]
            ]
        ));

        $this->assertEquals(
            ['and',
                ['A_type', '=', 'INTERNE'],
                ['A_id', '!in', [0, 100, 101, 100921, 100923]],
            ],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ['A_type', '=', 'INTERNE'],
                    ['A_id',   '>',  100923],
                ],
                ['and',
                    ['A_type', '=', 'INTERNE'],
                    ['A_id',   '>', 100921],
                    ['A_id',   '<', 100923],
                ],
                ['and',
                    ['A_type', '=', 'INTERNE'],
                    ['A_id',   '>', 101],
                    ['A_id',   '<', 100921],
                ],
                ['and',
                    ['A_type', '=', 'INTERNE'],
                    ['A_id',   '>', 100],
                    ['A_id',   '<', 101],
                ],
                ['and',
                    ['A_type', '=', 'INTERNE'],
                    ['A_id',   '>', 0],
                    ['A_id',   '<', 100],
                ],
                ['and',
                    ['A_type', '=', 'INTERNE'],
                    ['A_id',   '<', 0],
                ],
            ],
            $filter
                ->simplify([
                    'not_equal.normalization' => true,
                    'in.normalization_threshold' => 5,
                ])
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     * @todo implement normalization option and change this test to do it
     */
    public function test_simplify_between()
    {
        $filter = new LogicalFilter(
            ['field_1', '><', [2, 3]]
        );

        $this->assertEquals(
            ['and',
                ['field_1', '>', 2],
                ['field_1', '<', 3],
            ],
            $filter->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_setDefaultOptions()
    {
        $default_inrule_threshold = LogicalFilter::getDefaultOptions()['in.normalization_threshold'];

        LogicalFilter::setDefaultOptions([
            'in.normalization_threshold' => 20,
        ]);

        $filter = new LogicalFilter(
            ['field_1', 'in', ['a', 'b', 'c']]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', 'in', ['a', 'b', 'c']],
            $filter->toArray()
        );

        $filter->getRules(false)->addPossibilities(['d', 'e']);

        $this->assertEquals(
            ['a', 'b', 'c', 'd', 'e'],
            $filter->getRules()->getPossibilities()
        );

        $this->assertEquals(
            [
                'or',
                ['field_1', '=', 'a'],
                ['field_1', '=', 'b'],
                ['field_1', '=', 'c'],
                ['field_1', '=', 'd'],
                ['field_1', '=', 'e'],
            ],
            $filter
                ->simplify([

                ])
                // ->dump(true)
                ->toArray()
        );

        LogicalFilter::setDefaultOptions([
            'in.normalization_threshold' => $default_inrule_threshold,
        ]);
    }

    /**/
}
