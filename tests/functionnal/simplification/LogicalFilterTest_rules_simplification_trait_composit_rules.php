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
    public function test_simplify_on_atomic_rule_without_saving()
    {
        $filter = new LogicalFilter(["field", "=", true]);
        $this->assertTrue( $filter->hasSolution(false) );
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

    /**/
}
