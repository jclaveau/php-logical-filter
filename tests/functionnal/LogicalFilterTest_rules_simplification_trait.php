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

trait LogicalFilterTest_rules_simplification_trait
{
    /**
     */
    public function test_simplify_removeNegations_basic()
    {
        $filter = new LogicalFilter();

        $filter->and_([
            'not',
            ['field_2', 'above', 3],
        ]);

        $filter->simplify();

        $this->assertEquals(
            (new OrRule([
                new BelowRule('field_2', 3),
                new EqualRule('field_2', 3),
            ]))
            ->toArray(),
            $filter->toArray()
        );
    }

    /**
     */
    public function test_simplify_basic()
    {
        // $filter = (new LogicalFilter([
            // 'and',
            // ['field_5', '>', 'a'],
            // ['field_6', '<', 'a'],
            // [
                // '!',
                // ['field_5', '=', 'a'],
            // ],
        // ]);

        $filter = (new LogicalFilter([
            'and',
            ['field_5', '>', 3],
            ['field_5', '>', 5],
        ]))
        ->simplify()
        ;

        $this->assertEquals( ['field_5', '>', 5], $filter->toArray() );
    }

    /**
     */
    public function test_simplify_nested_and_operations()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_5', '>', 3],
            [
                'and',
                ['field_6', '>', 3],
                ['field_7', '>', 5],
            ],
        ]))
        ->simplify()
        ;

        $this->assertEquals( [
                'and',
                ['field_5', '>', 3],
                ['field_6', '>', 3],
                ['field_7', '>', 5],
            ],
            $filter
                // ->dump(true)
                ->toArray()
        );

        $filter = (new LogicalFilter([
            'and',
            ['field_5', '>', 3],
            [
                'and',
                ['field_6', '>', 3],
                ['field_7', '>', 5],
                [
                    'and',
                    ['field_8', '>', 3],
                    ['field_9', '>', 5],
                ],
            ],
        ]))
        ->simplify()
        ;

        $this->assertEquals( [
                'and',
                ['field_5', '>', 3],
                ['field_6', '>', 3],
                ['field_7', '>', 5],
                ['field_8', '>', 3],
                ['field_9', '>', 5],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_simplify_cleaning_operations_complex_multilevel()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['or',
                    ['and',
                        ['field_1', '=', 3],
                        ['field_5', '>', 3],
                    ],
                    ['and',
                        ['field_1', '=', 2],
                        ['field_6', '>', 4],
                    ],
                ],
                ['field_7', '=', 2],
            ]
        ))
        // ->dump()
        ->simplify()
        // ->dump()
        ;

        $this->assertEquals(
            ['or',
                ['and',
                    ['field_1', '=', 3],
                    ['field_5', '>', 3],
                    ['field_7', '=', 2],
                ],
                ['and',
                    ['field_1', '=', 2],
                    ['field_6', '>', 4],
                    ['field_7', '=', 2],
                ],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_simplify_nested_or_operations()
    {
        $filter = (new LogicalFilter([
            'or',
            ['field_5', '>', 3],
            [
                'or',
                ['field_6', '>', 3],
                ['field_7', '>', 5],
            ],
        ]))
        ->simplify()
        // ->dump(!true, false)
        ;

        $this->assertEquals( [
                'or',
                ['field_5', '>', 3],
                ['field_6', '>', 3],
                ['field_7', '>', 5],
            ],
            $filter->toArray()
        );


        $filter = (new LogicalFilter([
            'or',
            ['field_5', '>', 3],
            [
                'or',
                ['field_6', '>', 3],
                ['field_7', '>', 5],
                [
                    'or',
                    ['field_8', '>', 3],
                    ['field_9', '>', 5],
                ],
            ],
        ]))
        ->simplify()
        // ->dump(!true, false)
        ;

        $this->assertEquals( [
                'or',
                ['field_5', '>', 3],
                ['field_6', '>', 3],
                ['field_7', '>', 5],
                ['field_8', '>', 3],
                ['field_9', '>', 5],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_simplify_multiple_equals()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_5', '=', 3],
            ['field_5', '=', 5],
        ]))
        ->simplify()
        // ->dump(!true)
        ;

        $this->assertEmpty(
            $filter->getRules()->getOperands()
        );
    }

    /**
     */
    public function test_simplify_unify_multiple_aboves_or_below()
    {
        // above in and
        /**/
        $filter = (new LogicalFilter([
            'and',
            ['field_5', '>', null],  // equivalent to true
            ['field_5', '>', 3],
            ['field_5', '>', 5],
            ['field_5', '>', 5],
        ]))
        ->simplify()
        // ->dump(!true)
        ;

        $this->assertEquals(
            ['field_5', '>', 5],
            $filter->getRules()->toArray()
        );
        /**/

        // above in or
        $filter = (new LogicalFilter([
            'or',
            ['field_5', '>', null],  // equivalent to true
            ['field_5', '>', 3],
            ['field_5', '>', 5],
            ['field_5', '>', 5],
        ]))
        ->simplify()
        // ->dump(true)
        ;

        $this->assertEquals(
            ['field_5', '>', 3],
            $filter->getRules()->toArray()
        );
        /**/

        // below in and
        $filter = (new LogicalFilter([
            'and',
            ['field_5', '<', 3],
            ['field_5', '<', 5],
            ['field_5', '<', 5],
            ['field_5', '<', null],  // equivalent to true
        ]))
        ->simplify()
        // ->dump(!true)
        ;

        $this->assertEquals(
            ['field_5', '<', 3],
            $filter->getRules()->toArray()
        );
        /**/

        // below in or
        $filter = (new LogicalFilter([
            'or',
            ['field_5', '<', 3],
            ['field_5', '<', 5],
            ['field_5', '<', 5],
            ['field_5', '<', null],  // equivalent to true
        ]))
        ->simplify()
        // ->dump(!true)
        ;

        $this->assertEquals(
            ['field_5', '<', 5],
            $filter->getRules()->toArray()
        );
        /**/
    }

    /**
     */
    public function test_simplify_unifyOperands_inRecursion()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_5', '>', 'a'],
            [
                '!',
                ['field_5', '=', 'a'],
            ],
        ]))
        ->simplify([
            'force_logical_core' => true
        ])
        // ->dump(false, false)
        ;

        $this->assertEquals( [
            'or',
            [
                'and',
                ['field_5', '>', 'a'],
            ],
        ], $filter->toArray() );
    }

    /**
     */
    public function test_simplify_with_negation()
    {
        $filter = (new LogicalFilter([
            'and',
            ['field_5', '>', 'a'],
            [
                '!',
                ['field_5', '=', 'a'],
            ],
        ]))
        ->simplify()
        // ->dump(false, false)
        ;

        $this->assertEquals( ['field_5', '>', 'a'], $filter->toArray() );
    }

    /**
     */
    public function test_simplify_with_logicalCore()
    {
        $filter = (new LogicalFilter(
            ['field_5', '>', 'a']
        ))
        ->simplify(['force_logical_core' => true])
        // ->dump(false, false)
        ;

        $this->assertEquals( ['or', ['and', ['field_5', '>', 'a']]], $filter->toArray() );
        // This second assertion checks that the simplify process went
        // to its last step
        $this->assertTrue( $filter->hasSolution() );
    }

    /**
     */
    public function test_simplify_remove_monooperands_and()
    {
        $filter = (new LogicalFilter([
            'and',
            [
                'and',
                [
                    'and',
                    ['field_5', '=', 'a'],
                ],
            ],
        ]))
        ->simplify()
        // ->dump(false, false)
        ;

        $this->assertEquals( ['field_5', '=', 'a'], $filter->toArray() );
    }

    /**
     */
    public function test_simplify_remove_monooperands_or()
    {
        $filter = (new LogicalFilter([
            'or',
            [
                'or',
                [
                    'or',
                    ['field_5', '=', 'a'],
                ],
            ],
        ]))
        ->simplify()
        // ->dump(false, false)
        ;

        $this->assertEquals( ['field_5', '=', 'a'], $filter->toArray() );
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
    public function test_simplify_between_EqualRule_of_null_and_equal()
    {
        $filter = (new LogicalFilter(
            ['field_1', '=', null]
        ))
        ->and_(['field_1', '=', 3])
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
    public function test_simplify_between_EqualRule_of_null_and_above_or_below()
    {
        $filter = (new LogicalFilter(
            ['field_1', '=', null]
        ))
        ->and_(['field_1', '<', 3])
        ;

        $this->assertEquals(
            ['and'],
            $filter
                ->simplify()
                // ->dump(true)
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ['field_1', '=', null]
        ))
        ->and_(['field_1', '>', 3])
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
    public function test_rootifyDisjunctions_minimal()
    {
        $filter = (new LogicalFilter([
            'or',
            ['field_5', 'above', 'a'],
            ['field_5', 'below', 'a'],
        ]))
        ->simplify([
            'force_logical_core' => true
        ])
        // ->dump()
        ;

        $filter2 = (new LogicalFilter([
            'or',
            [
                'and',
                ['field_5', 'above', 'a'],
            ],
            [
                'and',
                ['field_5', 'below', 'a'],
            ],
        ]))
        ->simplify([
            'force_logical_core' => true
        ])
        // ->dump()
        ;

        $this->assertEquals(
            $filter2->toArray(),
            $filter->toArray()
        );
    }

    /**
     */
    public function test_rootifyDisjunctions_basic()
    {
        $filter = (new LogicalFilter([
            'and',
            [
                'or',
                ['field_4', 'above', 'a'],
                ['field_5', 'below', 'a'],
            ],
            ['field_6', 'equal', 'b'],
        ]))
        ->simplify()
        // ->dump()
        ;

        $filter2 = (new LogicalFilter([
            'or',
            [
                'and',
                ['field_4', 'above', 'a'],
                ['field_6', 'equal', 'b'],
            ],
            [
                'and',
                ['field_5', 'below', 'a'],
                ['field_6', 'equal', 'b'],
            ],
        ]))
        // ->dump(true)
        ;

        $this->assertEquals(
            $filter2->toArray(),
            $filter->toArray()
        );
    }

    /**
     */
    public function test_rootifyDisjunctions_complex()
    {
        $filter = new LogicalFilter();

        // (A' || A") && (B' || B") && (C' || C") <=>
        //    (A' && B' && C') || (A' && B' && C") || (A' && B" && C') || (A' && B" && C")
        // || (A" && B' && C') || (A" && B' && C") || (A" && B" && C') || (A" && B" && C");
        $filter->and_([
            'and',
            [
                'or',
                ['field_51', 'above', '5'],
                ['field_52', 'below', '5'],
            ],
            [
                'or',
                ['field_61', 'above', '6'],
                ['field_62', 'below', '6'],
            ],
            [
                'or',
                ['field_71', 'above', '7'],
                ['field_72', 'below', '7'],
            ],
        ]);

        $filter->simplify();

        $filter2 = new LogicalFilter;
        $filter2->and_([
            'or',
            [
                'and',
                ['field_51', 'above', '5'],
                ['field_61', 'above', '6'],
                ['field_71', 'above', '7'],
            ],
            [
                'and',
                ['field_52', 'below', '5'],
                ['field_61', 'above', '6'],
                ['field_71', 'above', '7'],
            ],
            [
                'and',
                ['field_51', 'above', '5'],
                ['field_62', 'below', '6'],
                ['field_71', 'above', '7'],
            ],
            [
                'and',
                ['field_52', 'below', '5'],
                ['field_62', 'below', '6'],
                ['field_71', 'above', '7'],
            ],
            [
                'and',
                ['field_51', 'above', '5'],
                ['field_61', 'above', '6'],
                ['field_72', 'below', '7'],
            ],
            [
                'and',
                ['field_52', 'below', '5'],
                ['field_61', 'above', '6'],
                ['field_72', 'below', '7'],
            ],
            [
                'and',
                ['field_51', 'above', '5'],
                ['field_62', 'below', '6'],
                ['field_72', 'below', '7'],
            ],
            [
                'and',
                ['field_52', 'below', '5'],
                ['field_62', 'below', '6'],
                ['field_72', 'below', '7'],
            ],
        ]);

        $this->assertEquals(
            $filter->toArray(),
            $filter2->toArray()
        );
    }

    /**
     */
    public function test_removeNegations_complex()
    {
        $filter = (new LogicalFilter([
            'or',
            ['field_1', 'below', 3],
            ['not', ['field_2', 'above', 3]],
            ['not', ['field_3', 'in', [7, 11, 13]]],
            ['not',
                [
                    'or',
                    ['field_4', 'below', 2],
                    ['field_5', 'in', ['a', 'b', 'c']],
                ],
            ],
        ]))
        ->simplify()
        // ->dump()
        ;

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
                    ['field_4', '=', 2],
                    ['field_5', '>', 'c'],
                ],
                ['and',
                    ['field_4', '>', 2],
                    ['field_5', '>', 'b'],
                    ['field_5', '<', 'c'],
                ],
                ['and',
                    ['field_4', '=', 2],
                    ['field_5', '>', 'b'],
                    ['field_5', '<', 'c'],
                ],
                ['and',
                    ['field_4', '>', 2],
                    ['field_5', '>', 'a'],
                    ['field_5', '<', 'b'],
                ],
                ['and',
                    ['field_4', '=', 2],
                    ['field_5', '>', 'a'],
                    ['field_5', '<', 'b'],
                ],
                ['and',
                    ['field_4', '>', 2],
                    ['field_5', '<', 'a'],
                ],
                ['and',
                    ['field_4', '=', 2],
                    ['field_5', '<', 'a'],
                ],
                ['field_2', '<', 3],
                ['field_2', '=', 3],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_hasSolution()
    {
        $this->assertFalse(
            (new LogicalFilter([
                'and',
                ['field_5', 'above', 'a'],
                ['field_5', 'below', 'a'],
            ]))
            ->hasSolution()
        );

        $this->assertFalse(
            (new LogicalFilter([
                'and',
                ['field_5', 'equal', 'a'],
                ['field_5', 'below', 'a'],
            ]))
            ->hasSolution()
        );

        $this->assertFalse(
            (new LogicalFilter([
                'and',
                ['field_5', 'equal', 'a'],
                ['field_5', 'above', 'a'],
            ]))
            ->hasSolution()
        );

        $this->assertTrue(
            (new LogicalFilter([
                'or',
                [
                    'and',
                    ['field_5', 'above', 'a'],
                    ['field_5', 'below', 'a'],
                ],
                ['field_6', 'equal', 'b'],
            ]))
            ->hasSolution()
        );
    }

    /**
     */
    public function test_hasSolution_on_null_filter()
    {
        // A filter has all solutions if it contains no rule.
        $filter = new LogicalFilter;
        $this->assertTrue( $filter->hasSolution() );
    }

    /**
     */
    public function test_hasSolution_saving_simplification()
    {
        $filter = new LogicalFilter([
            'and',
            ['field_1', '=', 'a'],
            ['field_1', '=', 'b'],
        ]);

        // don't save simplifications
        $this->assertFalse( $filter->hasSolution(false) );
        $this->assertEquals([
            'and',
            ['field_1', '=', 'a'],
            ['field_1', '=', 'b'],
        ], $filter->toArray() );

        // saving simplifications
        $this->assertFalse( $filter->hasSolution() );
        $this->assertEquals( ['and'], $filter->toArray() );
    }

    /**
     * @todo
     * @see https://github.com/jclaveau/php-logical-filter/issues/47
     */
    public function test_simplify_same_operands_which_are_operations()
    {
        $filter = (new LogicalFilter([
            'or',
            ['and',
                ['field_6', '>', 3],
                ['field_7', '>', 5],
            ],
            ['and',
                ['field_6', '>', 3],
                ['field_7', '>', 5],
            ],
        ]))
        ->simplify()
        ;

        $this->assertEquals( [
                'and',
                ['field_6', '>', 3],
                ['field_7', '>', 5],
            ],
            $filter
                // ->dump(true)
                ->toArray()
        );
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
    public function test_simplify_same_operands_with_same_casted_values()
    {
        $filter = (new LogicalFilter([
            'or',
            ['and',
                ['field_6', '=', 3],
                ['field_6', '=', '3'],
            ],
        ]))
        ->simplify()
        ;

        $this->assertEquals(
            ['field_6', '=', 3],
            $filter
                // ->dump(true)
                ->toArray()
        );
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
        ]))
        ->simplify()
        // ->dump(!true)
        ;

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
    public function test_simplify_with_not_in_rules()
    {
        $filter = (new LogicalFilter(
            ["and",
                [
                    "field",
                    "!in",
                    [
                        "PLOP",
                        "PROUT",
                    ]
                ],
                [
                    "field",
                    "!in",
                    [
                        "PLOUF",
                        "PROUT",
                    ]
                ],
                [
                    "field",
                    "!in",
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
                [
                    "adserver_type",
                    "in",
                    [
                        "INTERNE",
                        "PROD",
                    ]
                ],
                [
                    "adserver_id",
                    "!in",
                    [
                        0
                    ]
                ],
                [
                    "adserver_type",
                    "in",
                    [
                        "DIFF",
                        "INTERNE",
                    ]
                ],
                [
                    "adserver_id",
                    "!in",
                    [
                        0
                    ]
                ],
                [
                    "adserver_id",
                    "!in",
                    [
                        100,
                        101
                    ]
                ],
                [
                    "adserver_id",
                    "!in",
                    [
                        100921,
                        100923
                    ]
                ]
            ]
        ))
        ->simplify([
            // 'stop_after' => 'remove_negations',
            // 'stop_after' => 'rootify_disjunctions',
            // 'stop_after' => 'unify_atomic_operands',
        ])
        // ->dump(true)
        ;

        $this->assertEquals(
            ['or',
                ['and',
                    ['adserver_type', '=', 'INTERNE'],
                    ['adserver_id',   '>',  100923],
                ],
                ['and',
                    ['adserver_type', '=', 'INTERNE'],
                    ['adserver_id',   '>', 100921],
                    ['adserver_id',   '<', 100923],
                ],
                ['and',
                    ['adserver_type', '=', 'INTERNE'],
                    ['adserver_id',   '>', 101],
                    ['adserver_id',   '<', 100921],
                ],
                ['and',
                    ['adserver_type', '=', 'INTERNE'],
                    ['adserver_id',   '>', 100],
                    ['adserver_id',   '<', 101],
                ],
                ['and',
                    ['adserver_type', '=', 'INTERNE'],
                    ['adserver_id',   '>', 0],
                    ['adserver_id',   '<', 100],
                ],
                ['and',
                    ['adserver_type', '=', 'INTERNE'],
                    ['adserver_id',   '<', 0],
                ],
            ],
            $filter
                // ->dump(true)
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
            ["and",
                [
                    "adserver_type",
                    "=",
                    "INTERNE"
                ],
                [
                    "adserver_id",
                    ">",
                    0
                ],
                [
                    "adserver_id",
                    ">",
                    12
                ],
                [
                    "adserver_id",
                    ">=",
                    100
                ],
                [
                    "adserver_id",
                    "<",
                    2000
                ],
                [
                    "adserver_id",
                    "=",
                    100
                ],
                [
                    "date",
                    "=",
                    new \DateTime('2018-07-04')
                ],
                [
                    "event",
                    "=",
                    "impression"
                ],
            ]
        ))
        ->simplify([
        ])
        // ->dump(true)
        ;

        $this->assertEquals(
            ['or',
                ['and',
                    ['adserver_type', '=', 'INTERNE'],
                    ['adserver_id', '=', 100],
                    ['date', '=', new \DateTime('2018-07-04')],
                    ['event', '=', 'impression'],
                ],
            ],
            $filter
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
                ["type", "!in", ["a", "b", "c", "d"]],
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
                ["type", "!in", ["a", "b", "c", "d"]],
            ]
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ["type", "=",  "z"],
            $filter
                // ->dump(true)
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
        ))
        ->simplify()
        ;

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
                // ->dump(!true)
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ["and",
                ["type", "!=",  "z"],
                ["type", "!in", ["a", "b", "c", "d"]],
            ]
        ))
        ->simplify()
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
                // ->dump(!true)
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
            ['inrule.simplification_threshold' => 9]
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
    public function test_simplify_in_rules_changing_simplification_threshold()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["field", "in", [1, 2, 3, 4, 5, 6, 7]],
                ["field_2", "in", [1, 2, 3, 4, 5, 6, 7, 8]],
            ],
            null,
            ['inrule.simplification_threshold' => 5]
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
                $rule->setOptions(['inrule.simplification_threshold' => 10]);
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
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ["or",
                ["field", "=", 1],
                ["field", "=", 2],
            ],
            $filter
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
    public function test_simplification_empty_operations()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['or',
                    ['field', '=', 'plop'],
                ],
                ['and'],
            ]
        ))
        // ->dump()
        ;

        $this->assertEquals(
            ["and"],
            $filter
                ->simplify()
                // ->dump()
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ['and',
                ['or',
                    ['field', '=', 'plop'],
                ],
                ['or'],
            ]
        ))
        // ->dump()
        ;

        $this->assertEquals(
            ["or"],
            $filter
                ->simplify()
                // ->dump()
                ->toArray()
        );
    }

    /**/
}
