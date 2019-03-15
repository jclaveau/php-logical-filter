<?php
namespace JClaveau\LogicalFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;

/**
 */
trait LogicalFilterTest_simplify_remove_negations
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
            ['or',
                ['field_2', '<', 3],
                ['field_2', '=', 3],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_simplify_with_negation()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_5', '>', 'a'],
                ['!',
                    ['field_5', '=', 'a'],
                ],
            ]
        ))
        ->simplify()
        // ->dump()
        ;

        $this->assertEquals(['field_5', '>', 'a'], $filter->toArray());
    }

    /**
     */
    public function test_NotRule_of_long_and()
    {
        $filter = (new LogicalFilter(
            ['not',
                ['and',
                    ['field_1', '=', 2],
                    ['field_2', '<', 3],
                    ['field_3', '>', 4],
                ],
            ]
        ))
        // ->dump()
        ;

        $this->assertEquals(
            ['or',
                ['and', // 1
                    ['not', ['field_1', '=', 2]],
                    ['field_2', '<', 3],
                    ['field_3', '>',  4],
                ],

                ['and', // 2
                    ['field_1', '=', 2],
                    ['not', ['field_2', '<', 3]],
                    ['field_3', '>',  4],
                ],

                ['and', // 3
                    ['not', ['field_1', '=', 2]],
                    ['not', ['field_2', '<', 3]],
                    ['field_3', '>',  4],
                ],

                ['and', // 4
                    ['field_1', '=', 2],
                    ['field_2', '<', 3],
                    ['not', ['field_3', '>',  4]],
                ],

                ['and', // 5
                    ['not', ['field_1', '=', 2]],
                    ['field_2', '<', 3],
                    ['not', ['field_3', '>',  4]],
                ],

                ['and', // 6
                    ['field_1', '=', 2],
                    ['not', ['field_2', '<', 3]],
                    ['not', ['field_3', '>',  4]],
                ],

                ['and', // 7
                    ['not', ['field_1', '=', 2]],
                    ['not', ['field_2', '<', 3]],
                    ['not', ['field_3', '>',  4]],
                ],
            ],
            $filter
                ->getRules()
                ->negateOperand([])
                // ->dump(true)
                ->toArray()
        );
    }

    /**
     */
    public function test_NotRule_of_null()
    {
        $filter = (new LogicalFilter(
            ['field_1', '!=', null]
        ));

        $this->assertEquals(
            ['field_1', '!=', null],
            $filter->toArray()
        );

        $this->assertEquals(
            ['field_1', '!=', null],
            $filter
                ->simplify()
                // ->dump(!true)
                ->toArray()
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ['field_1', '!=', null],
                ],
            ],
            $filter
                ->simplify(['force_logical_core' => true])
                // ->dump(true)
                ->toArray()
        );

        $filter = (new LogicalFilter(
            ['not', ['field_1', '=', null]]
        ))
        // ->dump(true)
        ;

        $this->assertEquals(
            ['not', ['field_1', '=', null]],
            $filter
                ->toArray()
        );

        $this->assertEquals(
            ['field_1', '!=', null],
            $filter
                ->simplify()
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_unifyOperands_inRecursion()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['field_5', '>', 'a'],
                [
                    '!', ['field_5', '=', 'a'],
                ],
            ]
        ))
        ->simplify([
            'force_logical_core' => true
        ])
        // ->dump(false, false)
        ;

        $this->assertEquals(
            ['or',
                ['and',
                    ['field_5', '>', 'a'],
                ],
            ],
            $filter
                // ->dump()
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
                    ['or',
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

    /**/
}
