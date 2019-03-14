<?php
namespace JClaveau\LogicalFilter;

trait LogicalFilterTest_rules_descriptions
{
    /**
     */
    public function test_add_equal()
    {
        $filter = new LogicalFilter(
            ['field_1', '=', 2]
        );

        $this->assertEquals(
            ['field_1', '=', 2],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_below()
    {
        $filter = new LogicalFilter(
            ['field_1', '<', 2]
        );

        $this->assertEquals(
            ['field_1', '<', 2],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_above()
    {
        $filter = new LogicalFilter(
            ['field_1', '>', 2]
        );

        $this->assertEquals(
            ['field_1', '>', 2],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_and()
    {
        $filter = new LogicalFilter(
            ['and',
                ['field', '>', 3],
                ['field', '<', 5],
            ]
        );

        $this->assertEquals(
            ['and',
                ['field', '>', 3],
                ['field', '<', 5],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_or()
    {
        $filter = new LogicalFilter(
            ['or',
                ['field', '>', 3],
                ['field', '<', 5],
            ]
        );

        $this->assertEquals(
            ['or',
                ['field', '>', 3],
                ['field', '<', 5],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_not()
    {
        $filter = new LogicalFilter(
            ['not',
                ['field', '>', 3],
            ]
        );

        $this->assertEquals(
            ['not',
                ['field', '>', 3],
            ],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_in()
    {
        $filter = new LogicalFilter(
            ['field_1', 'in', ['a', 'b', 'c']]
        );

        $this->assertEquals(
            ['field_1', 'in', ['a', 'b', 'c']],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_above_or_equal()
    {
        $filter = new LogicalFilter(
            ['field_1', '>=', 2]
        );

        $this->assertEquals(
            ['field_1', '>=', 2],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_below_or_equal()
    {
        $filter = new LogicalFilter(
            ['field_1', '<=', 2]
        );

        $this->assertEquals(
            ['field_1', '<=', 2],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_not_equal()
    {
        $filter = new LogicalFilter(
            ['field_1', '!=', 'a']
        );

        $this->assertEquals(
            ['field_1', '!=', 'a'],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_not_in()
    {
        $filter = new LogicalFilter(
            ['field_1', '!in', [2, 3]]
        );

        $this->assertEquals(
            ['field_1', '!in', [2, 3]],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_between()
    {
        $filter = new LogicalFilter(
            ['field_1', '><', [2, 3]]
        );

        $this->assertEquals(
            ['field_1', '><', [2, 3]],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_between_or_equals_lower()
    {
        $filter = new LogicalFilter(
            ['field_1', '=><', [2, 3]]
        );

        $this->assertEquals(
            ['field_1', '=><', [2, 3]],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_between_or_equals_upper()
    {
        $filter = new LogicalFilter(
            ['field_1', '><=', [2, 3]]
        );

        $this->assertEquals(
            ['field_1', '><=', [2, 3]],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_between_or_equals_both()
    {
        $filter = new LogicalFilter(
            ['field_1', '=><=', [2, 3]]
        );

        $this->assertEquals(
            ['field_1', '=><=', [2, 3]],
            $filter->toArray()
        );
    }

    /**
     */
    public function test_add_regexp()
    {
        $filter = new LogicalFilter(
            ['field_1', 'regexp', '/^lalala*/']
        );

        $this->assertEquals(
            ['field_1', 'regexp', '/^lalala*/'],
            $filter->toArray()
        );
    }

    /**/
}
