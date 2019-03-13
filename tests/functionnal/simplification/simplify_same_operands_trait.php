<?php
namespace JClaveau\LogicalFilter;

trait LogicalFilterTest_simplify_same_operands
{
    /**
     */
    public function test_simplify_same_operands_above()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_5', '>', 3],
                ['field_5', '>', 5],
            ]
        ))
        ->simplify()
        ;

        $this->assertEquals(['field_5', '>', 5], $filter->toArray());
    }

    /**
     */
    public function test_simplify_same_operands_equals()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_5', '=', 3],
                ['field_5', '=', 5],
            ]
        ))
        ->simplify()
        // ->dump(!true)
        ;

        $this->assertEmpty(
            $filter->getRules()->getOperands()
        );
    }

    /**
     */
    public function test_simplify_same_operands_multiple_aboves_or_below()
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
    public function test_simplify_same_operands_EqualRule_of_null_and_equal()
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
     * @todo
     * @see https://github.com/jclaveau/php-logical-filter/issues/47
     */
    public function test_simplify_same_operands_which_are_operations()
    {
        $filter = (new LogicalFilter(
            ['or',
                ['and',
                    ['field_6', '>', 3],
                    ['field_7', '>', 5],
                ],
                ['and',
                    ['field_6', '>', 3],
                    ['field_7', '>', 5],
                ],
            ]
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ['and',
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
    public function test_simplify_same_operands_multiple_in()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["type", "in", ["a", "b", "c", "d"]],
                ["type", "in", ["b", "c", "d", 3]],
            ]
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ["type", "in", ["b", "c", "d"]],
            $filter
                // ->dump(!true)
                ->toArray()
        );
    }

    /**/
}
