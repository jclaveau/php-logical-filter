<?php
namespace JClaveau\LogicalFilter;

use JClaveau\LogicalFilter\Rule\AbstractOperationRule;
use JClaveau\LogicalFilter\Rule\OrRule;
use JClaveau\LogicalFilter\Rule\AndRule;
use JClaveau\LogicalFilter\Rule\NotRule;
use JClaveau\LogicalFilter\Rule\InRule;
use JClaveau\LogicalFilter\Rule\EqualRule;
use JClaveau\LogicalFilter\Rule\AboveRule;
use JClaveau\LogicalFilter\Rule\BelowRule;

trait LogicalFilterTest_rules_simplification_composit_rules
{
    /**
     */
    public function test_add_NotEqualRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '!=', 'a']
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '!=', 'a'],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_AboveOrEqualRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '>=', 2]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '>=', 2],
            $filter->toArray()
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
    public function test_add_BelowOrEqualRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '<=', 2]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '<=', 2],
            $filter->toArray()
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

    /**
     */
    public function test_simplify_between_EqualRule_of_null_and_NotEqualRule_of_null_giving_false()
    {
        $filter = (new LogicalFilter(
            ['field_1', '=', null]
        ))
        ->and_(['field_1', '!=', null])
        ;

        $this->assertEquals(
            ['and'],
            $filter
                // ->dump(true)
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_between_EqualRule_of_null_and_NotEqualRule_of_null_giving_true()
    {
        $this->markTestSkipped('Requires the support operands being TRUE or FALSE');
        // TODO this filter should just value true as it doesn't filter
        // anything. @see https://github.com/jclaveau/php-logical-filter/issues/38
        // to replace the OrRule by a TrueRule
        $filter = (new LogicalFilter(
            ['field_1', '=', null]
        ))
        ->or_(['field_1', '!=', null])
        ;

        $this->assertEquals(
            [
                'or',
                true,
                // ['field_1', '=', null],
                // ['field_1', '!=', null],
            ],
            $filter
                // ->dump(true)
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_between_NotEqualRule_of_null_and_above_or_below()
    {
        $filter = (new LogicalFilter(
            ['field_1', '!=', null]
        ))
        ->and_(['field_1', '<', 3])
        ;

        $this->assertEquals(
            ['field_1', '<', 3],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ['field_1', '!=', null]
        ))
        ->and_(['field_1', '>', 3])
        ;

        $this->assertEquals(
            ['field_1', '>', 3],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );

        // within OrRule
        $filter = (new LogicalFilter(
            ['field_1', '!=', null]
        ))
        ->or_(['field_1', '<', 3])
        ;

        $this->assertEquals(
            ['or',
                ['field_1', '!=', null],
                ['field_1', '<', 3],
            ],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ['field_1', '!=', null]
        ))
        ->or_(['field_1', '>', 3])
        ;

        $this->assertEquals(
            ['or',
                ['field_1', '!=', null],
                ['field_1', '>', 3],
            ],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_between_NotEqualRule_of_null_and_equal()
    {
        $filter = (new LogicalFilter(
            ['field_1', '!=', null]
        ))
        ->and_(['field_1', '=', 3])
        ;

        $this->assertEquals(
            ['field_1', '=', 3],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_NotEqualRule()
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
    }

    /**
     */
    public function test_add_InRule()
    {
        $filter = new LogicalFilter(
            ['field_1', 'in', ['a', 'b', 'c']],
            null,
            ['inrule.simplification_threshold' => 20]
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
    public function test_removeNegations_complex_without_normalization()
    {
        $filter = (new LogicalFilter(
            ['or',
                ['field_1', '<', 3],
                ['not', ['field_2', '>', 3]],
                ['not', ['field_3', 'in', [7, 11, 13]]],
                ['not',
                    [
                        'or',
                        ['field_4', '<', 2],
                        ['field_5', 'in', ['a', 'b', 'c']],
                    ],
                ],
            ]
        ));

        $this->assertEquals(
            ['or',
                ['field_1', '<', 3],
                ['field_3', '!in', [7, 11, 13]], // TODO merge during remove negation?
                // ['not', ['field_3', 'in', [7, 11, 13]]],
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
            ],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_rootifyDisjunction_with_not_normalizable_in_inside_or()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_1', '!in', [1, 2]],
                ['or',
                    ['field_2', 'in', [1, 2, 3, 4, 5, 6]],
                    ['field_1', 'in', [8, 9]],
                ],
            ]
        ))
        // ->dump()
        ;

        $this->assertEquals(
            ['or',
                ['and',
                    ['field_1', '!in', [1, 2]],
                    ['field_2', 'in', [1, 2, 3, 4, 5, 6]],
                ],
                ['field_1', 'in', [8, 9]],
            ],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     * Checks that when a rule is invalidated for a given field (c_id),
     * all the "and_case" is invalidated (a_id rules too)
     */
    public function test_invalidation_of_a_field_discards_all_the_case()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['c_id', 'in', ['1353']],
                ['not',
                    ['or',
                        ['c_id', 'in', [1352, 1353]],
                        ['a_id', 'in', [100921, 100923]],
                    ],
                ],
            ]
        ))
        // ->dump()
        ;

        $this->assertEquals(
            ['and'],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_not_in_rules_with_normalization()
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
    public function test_simplify_NotIn_empty()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_1', '=', 'a'],
                ['field_2', '!in', []],
            ]
        ))
        // ->dump(true)
        ->simplify()
        // ->dump(true)
        ;

        $this->assertEquals(
            ['and',
                ['field_1', '=', 'a'],
                ['field_2', '!in', []], // TODO this should be a TrueRule
            ],
            $filter->toArray()
        );

        $this->assertTrue( $filter->hasSolution() );
    }

    /**
     */
    public function test_do_not_simplify_if_possibilities_count_above_threshold()
    {
        // The constant InRule::simplification_threshold forbids the simplification
        // of the InRule to avoid combinations explosion (and fatal error
        // due to more than allowed ram used)
        $filter = new LogicalFilter(['field', 'in', [
            0,
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12,
            13,
            14,
            15,
            16,
            17,
            18,
            19,
            20,
            21,
            22,
            23,
            24,
            25,
            26,
            27,
        ]]);

        $this->assertEquals(
            ['field', 'in', [
                0,
                1,
                2,
                3,
                4,
                5,
                6,
                7,
                8,
                9,
                10,
                11,
                12,
                13,
                14,
                15,
                16,
                17,
                18,
                19,
                20,
                21,
                22,
                23,
                24,
                25,
                26,
                27,
            ]],
            $filter
                ->simplify()
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_add_NotInRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '!in', [2, 3]]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '!in', [2, 3]],
            $filter->toArray()
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
    public function test_simplify_of_InRule_with_simplification_disallowed()
    {
        $filter = (new LogicalFilter([
            "and",
            ["type", "in", ["lolo","lala","lili"]],
            ["type", "in", ["lolo","lala","lili","lulu"]],
            ["user", "in", [10,23,27,28,30,32,56,60,61,62,65,74,76,77,86,98,99,104,110,111,116,118,123,130,134,135,142,144,146,147,148,149,150,151,154,157,159,163,170,174,178,179,188,198,200,209,210,211,212,213,222,235,236,243,244,245,246,259,262,266,268,270,271,272,273]],
        ]));

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
    public function test_simplify_on_atomic_rule_without_saving()
    {
        $filter = new LogicalFilter(["field", "=", true]);
        $this->assertTrue( $filter->hasSolution(false) );
    }

    /**
     */
    public function test_simplify_with_in_rules()
    {
        $filter = (new LogicalFilter(
            [
                "and",
                [
                    "field",
                    "in",
                    [
                        "PLOP",
                        "PROUT",
                    ]
                ],
                [
                    "field",
                    "in",
                    [
                        "PLOUF",
                        "PROUT",
                    ]
                ],
                [
                    "field",
                    "in",
                    [
                        "PROUT",
                        "PLOUF",
                        "POUET",
                    ]
                ],
            ]
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ['field', '=', 'PROUT'],
            $filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_not_in_rules()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["field", "!in", [
                    "PLOP",
                    "PROUT",
                ]],
                ["field", "!in", [
                    "PLOUF",
                    "PROUT",
                ]],
                ["field", "!in", [
                    "PROUT",
                    "PLOUF",
                    "POUET",
                ]],
            ]
        ));

        // reversed alphabetical order:
        $this->assertEquals(
            ['field', '!in', ['PLOP', 'PROUT', 'PLOUF', 'POUET']],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );

        // reversed alphabetical order:
        // + PROUT
        // + POUET
        // + PLOUF
        // + PLOP
        $this->assertEquals(
            ["or",
                ["field", ">", "PROUT"],
                ["field", "<", "PLOP"],
                ["and",
                    ["field", ">", "POUET"],
                    ["field", "<", "PROUT"],
                ],
                ["and",
                    ["field", ">", "PLOUF"],
                    ["field", "<", "POUET"],
                ],
                ["and",
                    ["field", ">", "PLOP"],
                    ["field", "<", "PLOUF"],
                ],
            ],
            $filter
                ->simplify([
                    'not_equal.normalization' => true,
                    'in.normalization_threshold' => 4,
                ])
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_with_big_combinations()
    {
        $filter = (new LogicalFilter(
            [
                "and",
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
     */
    public function test_simplify_simplifyDifferentOperands_equal_and_in()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["type", "=",  "a"],
                ["type", "in", ["a", "b", "c", "d"]],
            ]
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ["type", "=",  "a"],
            $filter
                // ->dump(true)
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ["and",
                ["type", "=",  "z"],
                ["type", "in", ["a", "b", "c", "d"]],
            ]
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ["or"],
            $filter
                // ->dump(true, ['mode' => 'export'])
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_simplifyDifferentOperands_equal_and_not_in()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["type", "=",  "a"],
                ["type", "!in", ["b", "c", "d"]],
            ]
        ));

        $this->assertEquals(
            ["type", "=",  "a"],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ["and",
                ["type", "=",  "a"],
                ["type", "!in", ["a", "b", "c", "d"]],
            ]
        ));

        $this->assertEquals(
            ["and"],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_in_rules_changing_simplification_threshold()
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
    public function test_simplify_in_without_threshold()
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
    public function test_simplify_differentOperands_in_with_not_in()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["field", "in", [1, 2, 3, 4]],
                ["field", "!in", [3, 4, 5, 6]],
            ]
        ));

        $this->assertEquals(
                ["field", "in", [1, 2]],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplification_cache_copied()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["field2", "in", [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21]],
            ]
        ))
        // ->dump()
        ;

        $inRule = $filter->getRules(false)->simplify();

        $inRule
            ->setPossibilities([22, 23, 24]);

        $this->assertEquals(
            ["and",
                ["field2", "in", [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21]],
            ],
            $filter
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_differentOperands_in_above_below()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["or",
                    ["field2", "=", 4],
                    ["field2", ">", 10],
                    ["field2", "<", 3],
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
     */
    public function test_simplify_simplifySameOperands_multiple_in()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["type", "in", ["a", "b", "c", "d"]],
                ["type", "in", ["b", "c", "d"]],
                ["type", "=",  "c"],
            ]
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ["type", "=",  "c"],
            $filter
                // ->dump(!true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_simplifyDifferentOperands_different_and_not_in()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["type", "!=",  "a"],
                ["type", "!in", ["a", "b", "c", "d"]],
            ]
        ));

        $this->assertEquals(
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
            $filter
                ->simplify([
                    'in.normalization_threshold' => 4,
                    'not_equal.normalization'    => true,
                ])
                // ->dump(!true)
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ["and",
                ["type", "!=",  "z"],
                ["type", "!in", ["a", "b", "c", "d"]],
            ]
        ))
        ->simplify([
            'in.normalization_threshold' => 5,
            'not_equal.normalization'    => true,
        ])
        ;

        $this->assertEquals(
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
            $filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**/
}
