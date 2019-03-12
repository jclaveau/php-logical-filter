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

trait LogicalFilterTest_rules_normalization_composit_rules
{
    /**
     */
    public function test_add_NotEqualRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '!=', 'a']
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '!=', 'a'],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_AboveOrEqualRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '>=', 2]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '>=', 2],
            $filter->toArray()
        );

        $this->assertEquals(
            ['field_1', '>=', 2],
            $filter
                ->simplify()
                ->toArray()
        );

        $this->assertEquals(
            ['or',
                ['field_1', '>', 2],
                ['field_1', '=', 2],
            ],
            $filter
                ->simplify(['above_or_equal.normalization' => true])
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_add_BelowOrEqualRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '<=', 2]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '<=', 2],
            $filter->toArray()
        );

        $this->assertEquals(
            ['field_1', '<=', 2],
            $filter->simplify()->toArray()
        );

        $this->assertEquals(
            ['or',
                ['field_1', '<', 2],
                ['field_1', '=', 2],
            ],
            $filter
                ->simplify(['below_or_equal.normalization' => true])
                ->toArray()
        );
    }

    /**
     */
    public function test_add_NotInRule()
    {
        $filter = new LogicalFilter(
            ['field_1', '!in', [2, 3]]
        );

        // toArray must be iso to the provided descrition
        $this->assertEquals(
            ['field_1', '!in', [2, 3]],
            $filter->toArray()
        );
    }

    /**/
}
