<?php
namespace JClaveau\LogicalFilter\Tests;

use JClaveau\LogicalFilter\LogicalFilter;

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

        $this->assertEquals(
            ['field_5', '>', 5],
            $filter->toArray()
        );
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
        // ->dump(true)
        ;

        $this->assertEquals(
            ['and'],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_simplify_same_operands_multiple_above_in_and()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_5', '>', null],  // equivalent to true
                ['field_5', '>', 3],
                ['field_5', '>', 5],
                ['field_5', '>', 5],
            ]
        ))
        ->simplify()
        // ->dump(!true)
        ;

        $this->assertEquals(
            ['field_5', '>', 5],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_simplify_same_operands_multiple_above_in_or()
    {
        $filter = (new LogicalFilter(
            ['or',
                ['field_5', '>', null],  // equivalent to true
                ['field_5', '>', 3],
                ['field_5', '>', 5],
                ['field_5', '>', 5],
            ]
        ))
        ->simplify()
        // ->dump(true)
        ;

        $this->assertEquals(
            ['field_5', '>', 3],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_simplify_same_operands_multiple_below_in_and()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_5', '<', 3],
                ['field_5', '<', 5],
                ['field_5', '<', 5],
                ['field_5', '<', null],  // equivalent to true
            ]
        ))
        ->simplify()
        // ->dump(!true)
        ;

        $this->assertEquals(
            ['field_5', '<', 3],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_simplify_same_operands_multiple_below_in_or()
    {
        // below in or
        $filter = (new LogicalFilter(
            ['or',
                ['field_5', '<', 3],
                ['field_5', '<', 5],
                ['field_5', '<', 5],
                ['field_5', '<', null],  // equivalent to true
            ]
        ))
        ->simplify()
        // ->dump(!true)
        ;

        $this->assertEquals(
            ['field_5', '<', 5],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_simplify_same_operands_equals_with_null()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_1', '=', null],
                ['field_1', '=', 3],
            ]
        ))
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
        $filter = (new LogicalFilter(
            ['or',
                ['and',
                    ['field_6', '=', 3],
                    ['field_6', '=', '3'],
                ],
            ]
        ))
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

    /**
     */
    public function test_simplify_same_operands_in()
    {
        $filter = (new LogicalFilter(
            ["and",
                ["field", "in", ["PLOP", "PROUT", "PLOUF"]],
                ["field", "in", ["PROUT", "PLOUF", "POUET"]],
            ]
        ))
        ->simplify()
        ;

        $this->assertEquals(
            ["field", "in", ["PROUT", "PLOUF"]],
            $filter
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_same_operands_not_in()
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

    /**/
}
