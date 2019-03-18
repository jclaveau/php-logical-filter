<?php
namespace JClaveau\LogicalFilter\Tests;

use JClaveau\LogicalFilter\LogicalFilter;

trait LogicalFilterTest_simplify_different_operands
{
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
    public function test_BelowRule_and_AboveRule_are_strictly_compared()
    {
        $this->assertFalse(
            (new LogicalFilter([
                'and',
                ['field_1', '=', 3],
                ['field_1', '<', 3],
            ]))
            ->hasSolution()
        );

        $this->assertFalse(
            (new LogicalFilter([
                'and',
                ['field_1', '=', 3],
                ['field_1', '>', 3],
            ]))
            ->hasSolution()
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
    public function test_simplify_different_operands_in_and_equal()
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
