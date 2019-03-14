<?php
namespace JClaveau\LogicalFilter;

trait LogicalFilterTest_rules_descriptions
{
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

    /**/
}
