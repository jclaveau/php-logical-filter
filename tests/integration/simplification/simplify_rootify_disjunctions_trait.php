<?php
namespace JClaveau\LogicalFilter\Tests;

use JClaveau\VisibilityViolator\VisibilityViolator;

use JClaveau\LogicalFilter\LogicalFilter;

/**
 */
trait LogicalFilterTest_simplify_rootify_disjuctions
{
    /**
     */
    public function test_rootifyDisjunctions_basic()
    {
        $filter = (new LogicalFilter(
            ['and',
                ['or',
                    ['field_4', 'above', 'a'],
                    ['field_5', 'below', 'a'],
                ],
                ['field_6', 'equal', 'b'],
            ]
        ))
        ->simplify()
        // ->dump()
        ;

        $filter2 = (new LogicalFilter(
            ['or',
                ['and',
                    ['field_4', 'above', 'a'],
                    ['field_6', 'equal', 'b'],
                ],
                ['and',
                    ['field_5', 'below', 'a'],
                    ['field_6', 'equal', 'b'],
                ],
            ]
        ))
        // ->dump(true)
        ;

        $this->assertEquals(
            $filter2->toArray(),
            $filter->toArray()
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
    public function test_rootifyDisjunctions_complex()
    {
        $filter = new LogicalFilter();

        // (A' || A") && (B' || B") && (C' || C") <=>
        //    (A' && B' && C') || (A' && B' && C") || (A' && B" && C') || (A' && B" && C")
        // || (A" && B' && C') || (A" && B' && C") || (A" && B" && C') || (A" && B" && C");
        $filter->and_(
            ['and',
                ['or',
                    ['field_51', 'above', '5'],
                    ['field_52', 'below', '5'],
                ],
                ['or',
                    ['field_61', 'above', '6'],
                    ['field_62', 'below', '6'],
                ],
                ['or',
                    ['field_71', 'above', '7'],
                    ['field_72', 'below', '7'],
                ],
            ]
        );

        $filter->simplify();

        $filter2 = new LogicalFilter;
        $filter2->and_(
            ['or',
                ['and',
                    ['field_51', 'above', '5'],
                    ['field_61', 'above', '6'],
                    ['field_71', 'above', '7'],
                ],
                ['and',
                    ['field_52', 'below', '5'],
                    ['field_61', 'above', '6'],
                    ['field_71', 'above', '7'],
                ],
                ['and',
                    ['field_51', 'above', '5'],
                    ['field_62', 'below', '6'],
                    ['field_71', 'above', '7'],
                ],
                ['and',
                    ['field_52', 'below', '5'],
                    ['field_62', 'below', '6'],
                    ['field_71', 'above', '7'],
                ],
                ['and',
                    ['field_51', 'above', '5'],
                    ['field_61', 'above', '6'],
                    ['field_72', 'below', '7'],
                ],
                ['and',
                    ['field_52', 'below', '5'],
                    ['field_61', 'above', '6'],
                    ['field_72', 'below', '7'],
                ],
                ['and',
                    ['field_51', 'above', '5'],
                    ['field_62', 'below', '6'],
                    ['field_72', 'below', '7'],
                ],
                ['and',
                    ['field_52', 'below', '5'],
                    ['field_62', 'below', '6'],
                    ['field_72', 'below', '7'],
                ],
            ]
        );

        $this->assertEquals(
            $filter->toArray(),
            $filter2->toArray()
        );
    }

    /**/
}
