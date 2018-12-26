<?php
namespace JClaveau\LogicalFilter\Rule;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class BetweenRuleTest extends \AbstractTest
{
    /**
     */
    public function test_hasSolution()
    {
        $rule = new BetweenRule('field', [4, 5]);
        $this->assertTrue( $rule->hasSolution() );

        $rule = new BetweenRule('field', [5, 4]);
        $this->assertFalse( $rule->hasSolution() );

        $rule = new BetweenRule('field', [5, 5]);
        $this->assertFalse( $rule->hasSolution() );
    }

    /**
     */
    public function test_getField()
    {
        $rule = new BetweenRule('field', [4, 6]);

        $operands = $rule->getOperands();
        $operands[0] = new BelowRule('field_2', 3);
        $rule->setOperands($operands);

        try {
            $this->assertFalse( $rule->getField() );
            $this->assertTrue(
                false,
                "An exception explaining that the two operands have different fields "
            );
        }
        catch (\Exception $e) {
            $this->assertTrue(true, "Exception thrown: ".$e->getMessage());
        }
    }

    /**
     */
    public function test_between_or_equal()
    {
        // between
        $filter = (new LogicalFilter(["field_1", "><", [1, 2]]));

        $this->assertEquals(
            ["field_1", "><", [1, 2]],
            $filter
                // ->dump()
                ->toArray()
        );

        $this->assertEquals(
            ['and',
                ["field_1", ">", 1],
                ["field_1", "<", 2],
            ],
            $filter
                ->simplify()
                ->toArray()
        );

        // between or equal lower limit
        $filter = (new LogicalFilter(["field_1", "=><", [1, 2]]));

        $this->assertEquals(
            ["field_1", "=><", [1, 2]],
            $filter->toArray()
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ['field_1', '>=', 1],
                    ['field_1', '<', 2],
                ],
            ],
            $filter
                ->copy()
                ->simplify()
                // ->dump()
                ->toArray()
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ["field_1", ">", 1],
                    ["field_1", "<", 2],
                ],
                ["field_1", "=", 1],
            ],
            $filter
                ->simplify(['above_or_equal.normalization' => true])
                // ->dump()
                ->toArray()
        );

        // between or equal upper limit
        $filter = (new LogicalFilter(["field_1", "><=", [1, 2]]));

        $this->assertEquals(
            ["field_1", "><=", [1, 2]],
            $filter->toArray()
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ["field_1", ">", 1],
                    ["field_1", "<", 2],
                ],
                ["field_1", "=", 2],
            ],
            $filter
                ->simplify(['below_or_equal.normalization' => true])
                ->toArray()
        );

        // between or equal upper limit
        $filter = (new LogicalFilter(["field_1", "><=", [1, 2]]));

        $this->assertEquals(
            ["field_1", "><=", [1, 2]],
            $filter->toArray()
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ['field_1', '>', 1],
                    ['field_1', '<=', 2],
                ],
            ],
            $filter
                ->simplify(['below_or_equal.normalization' => false])
                ->toArray()
        );

        // between or equal both limits
        $filter = (new LogicalFilter(["field_1", "=><=", [1, 2]]));

        $this->assertEquals(
            ["field_1", "=><=", [1, 2]],
            $filter->toArray()
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ["field_1", ">", 1],
                    ["field_1", "<", 2],
                ],
                ["field_1", "=", 1],
                ["field_1", "=", 2],
            ],
            $filter
                ->copy()
                ->simplify([
                    'below_or_equal.normalization' => true,
                    'above_or_equal.normalization' => true,
                ])
                // ->dump()
                ->toArray()
        );

        $this->assertEquals(
            ['or',
                ['and',
                    ["field_1", ">=", 1],
                    ["field_1", "<=", 2],
                ],
            ],
            $filter
                ->copy()
                ->simplify([
                    'below_or_equal.normalization' => false,
                    'above_or_equal.normalization' => false,
                ])
                // ->dump()
                ->toArray()
        );
    }

    /**
     */
    public function test_simplify_between_or_equal_with_equal_limits()
    {
        $start = new \DateTime('2018-06-11');
        $end   = new \DateTime('2018-06-11');

        $this->assertEquals(
            ['date', '=', $start],
            (new LogicalFilter([
                'and',
                ['date',  '=><', [$start, $end]],
            ]))
            ->simplify()
            // ->dump()
            ->toArray()
        );

        $this->assertEquals(
            ['date', '=', $start],
            (new LogicalFilter([
                'and',
                ['date',  '><=', [$start, $end]],
            ]))
            ->simplify()
            // ->dump()
            ->toArray()
        );

        $this->assertEquals(
            ['date', '=', $start],
            (new LogicalFilter([
                'and',
                ['date',  '=><=', [$start, $end]],
            ]))
            ->simplify()
            // ->dump()
            ->toArray()
        );

        $this->assertEquals(
            ['and'],
            (new LogicalFilter([
                'and',
                ['date',  '><', [$start, $end]],
            ]))
            ->simplify()
            // ->dump()
            ->toArray()
        );
    }

    /**/
}
