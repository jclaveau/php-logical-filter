<?php
namespace JClaveau\LogicalFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;

/**
 * Cleaning gathers different operations removing useless operation
 * rules in the rule tree
 */
trait LogicalFilterTest_simplify_clean
{
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
    public function test_simplify_remove_monooperands_and()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['and',
                    ['and',
                        ['field_5', '=', 'a'],
                    ],
                ],
            ]
        ))
        ->simplify()
        // ->dump(false, false)
        ;

        $this->assertEquals(['field_5', '=', 'a'], $filter->toArray());
    }

    /**
     */
    public function test_simplify_remove_monooperands_or()
    {
        $filter = (new LogicalFilter(
            ['or',
                ['or',
                    ['or',
                        ['field_5', '=', 'a'],
                    ],
                ],
            ]
        ))
        ->simplify()
        // ->dump(false, false)
        ;

        $this->assertEquals(['field_5', '=', 'a'], $filter->toArray());
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
     * Checks that when a rule is invalidated for a given field (c_id),
     * all the "and_case" is invalidated (a_id rules too)
     */
    public function test_invalidation_of_a_field_discards_all_the_case()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['c_id', 'in', ['1353']],  // This is incompatible
                ['not',
                    ['or',
                        ['c_id', 'in', [1352, 1353]], // with this
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

    /**/
}
