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

        $this->assertTrue( $filter->getRules()->isSimplified() );

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
                    ['field_7', '=', 2],
                    ['field_1', '=', 3],
                    ['field_5', '>', 3],
                ],
                ['and',
                    ['field_7', '=', 2],
                    ['field_1', '=', 2],
                    ['field_6', '>', 4],
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
        ->simplify(['stop_after' => AbstractOperationRule::remove_negations])
        ;

        $filter2 = new LogicalFilter([
            'and',
            [
                'or',
                ['field_1', 'below', 3],
                // ['not', ['field_2', 'above', 3]],
                [
                    'or',
                    ['field_2', 'below', 3],
                    ['field_2', 'equal', 3],
                ],
                // ['not', ['field_3', 'in', [7, 11, 13]]],
                [
                    'and',
                    [
                        'or',
                        ['field_3', 'above', 7],
                        ['field_3', 'below', 7],
                    ],
                    [
                        'or',
                        ['field_3', 'above', 11],
                        ['field_3', 'below', 11],
                    ],
                    [
                        'or',
                        ['field_3', 'above', 13],
                        ['field_3', 'below', 13],
                    ],
                ],
                // ['not',
                    // [
                        // 'or',
                        // ['field_4', 'below', 2],
                        // ['field_5', 'in', ['a', 'b', 'c']],
                    // ],
                // ],
                [
                    'and',
                    [
                        'or',
                        ['field_4', 'above', 2],
                        ['field_4', 'equal', 2],
                    ],
                    [
                        'and',
                        [
                            'or',
                            ['field_5', 'above', 'a'],
                            ['field_5', 'below', 'a'],
                        ],
                        [
                            'or',
                            ['field_5', 'above', 'b'],
                            ['field_5', 'below', 'b'],
                        ],
                        [
                            'or',
                            ['field_5', 'above', 'c'],
                            ['field_5', 'below', 'c'],
                        ],
                    ],
                ],
            ],
        ]);

        $this->assertEquals(
            $filter2->toArray(),
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

    /**/
}
