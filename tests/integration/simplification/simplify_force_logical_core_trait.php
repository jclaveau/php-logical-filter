<?php
namespace JClaveau\LogicalFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;

trait LogicalFilterTest_simplify_force_logical_core
{
    /**
     */
    public function test_force_logical_core_minimal()
    {
        $filter = (new LogicalFilter(
            ['or',
                ['field_5', '>', 'a'],
                ['field_5', '<', 'a'],
            ]
        ))
        ->simplify([
            'force_logical_core' => true
        ])
        // ->dump()
        ;

        $filter2 = (new LogicalFilter(
            ['or',
                ['and',
                    ['field_5', '>', 'a'],
                ],
                ['and',
                    ['field_5', '<', 'a'],
                ],
            ]
        ))
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
    public function test_forceLogicalCore_with_AtomicRule_at_root()
    {
        $filter = new LogicalFilter( ['field_1', '=', 3] );

        $this->assertEquals(
            ['or',
                ['and',
                    ['field_1', '=', 3],
                ],
            ],
            $filter
                ->simplify(['force_logical_core' => true])
                ->toArray()
        );
    }

    /**
     */
    public function test_forceLogicalCore_with_AndRule_at_root()
    {
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->and_(['field_2', '=', 4])
            // ->dump()
            ;

        $this->assertEquals(
            [
                'or',
                [
                    'and',
                    ['field_1', '=', 3],
                    ['field_2', '=', 4],
                ],
            ],
            $filter
                ->simplify(['force_logical_core' => true])
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_forceLogicalCore_with_OrRule_at_root()
    {
        $filter = (new LogicalFilter( ['field_1', '=', 3] ))
            ->or_(['field_2', '=', 4])
            // ->dump()
            ;

        $this->assertEquals(
            [
                'or',
                [
                    'and',
                    ['field_1', '=', 3],
                ],
                [
                    'and',
                    ['field_2', '=', 4],
                ],
            ],
            $filter
                ->simplify(['force_logical_core' => true])
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_forceLogicalCore_with_NotRule_at_root()
    {
        $filter = (new LogicalFilter( ['not', ['field_1', '=', 3]] ))
            // ->dump()
            ;

        $result = VisibilityViolator::callHiddenMethod(
            $filter->getRules(), 'forceLogicalCore'
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ['not', ['field_1', '=', 3]],
                ],
            ],
            $result->toArray()
        );
    }

    /**
     */
    public function test_forceLogicalCore_with_AboveOrEqualRule_at_root()
    {
        $filter = (new LogicalFilter( ['field_1', '>=', 3] ))
            // ->dump()
            ;

        $result = VisibilityViolator::callHiddenMethod(
            $filter->getRules(), 'forceLogicalCore'
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ['field_1', '>=', 3],
                ],
            ],
            $result->toArray()
        );
    }

    /**
     */
    public function test_forceLogicalCore_with_BelowOrEqualRule_at_root()
    {
        $filter = (new LogicalFilter( ['field_1', '<=', 3] ))
            // ->dump()
            ;

        $result = VisibilityViolator::callHiddenMethod(
            $filter->getRules(), 'forceLogicalCore'
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ['field_1', '<=', 3],
                ],
            ],
            $result->toArray()
        );
    }

    /**
     */
    public function test_forceLogicalCore_with_InRule_at_root()
    {
        $filter = (new LogicalFilter(
                ['field_1', 'in', [3, 4, 5, 6]]
            ))
            // ->dump()
            ;

        $result = VisibilityViolator::callHiddenMethod(
            $filter->getRules(), 'forceLogicalCore'
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ['field_1', 'in', [3, 4, 5, 6]],
                ],
            ],
            $result->toArray()
        );
    }

    /**/
}
