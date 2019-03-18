<?php
namespace JClaveau\LogicalFilter\Tests;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;
use function JClaveau\LogicalFilter\key;
use function JClaveau\LogicalFilter\value;

class PhpFiltererTest extends \AbstractTest
{
    /**
     */
    public function test_equal()
    {
        $filter = new LogicalFilter(
            ['col_1', '=', 2]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            2,
        ]);

        // print_r($filtered_array);exit;
        $this->assertEquals(
            [
                1 => ['col_1' => 2],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_equal_null()
    {
        $filter = new LogicalFilter(
            ['col_1', '=', null]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                2 => ['col_1' => null],
                3 => ['col_2' => 2],
                4 => 2,
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_not_equal()
    {
        $filter = new LogicalFilter(
            ['col_1', '!=', 2]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                0 => ['col_1' => 1],
                2 => ['col_1' => null],
                3 => ['col_2' => 2],
                4 => 2,
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_not_equal_null()
    {
        $filter = new LogicalFilter(
            ['col_1', '!=', null]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                0 => ['col_1' => 1],
                1 => ['col_1' => 2],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_above()
    {
        $filter = new LogicalFilter(
            ['col_1', '>', 1]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                1 => ['col_1' => 2],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_below()
    {
        $filter = new LogicalFilter(
            ['col_1', '<', 2]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                0 => ['col_1' => 1],
                // NB: null is not considered "below 2" contrary to the
                // behavior of min().
                // Use ['or', ['col_1', '<', 2], ['col_1', '=', null]] instead
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_above_or_equal()
    {
        $filter = new LogicalFilter(
            ['col_1', '>=', 2]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            ['col_1' => 3],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                1 => ['col_1' => 2],
                4 => ['col_1' => 3],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_below_or_equal()
    {
        $filter = new LogicalFilter(
            ['col_1', '<=', 2]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            ['col_1' => 3],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                0 => ['col_1' => 1],
                1 => ['col_1' => 2],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_between()
    {
        $filter = new LogicalFilter(
            ['col_1', '><', [1, 3]]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            ['col_1' => 3],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                1 => ['col_1' => 2],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_between_or_equal_lower()
    {
        $filter = new LogicalFilter(
            ['col_1', '=><', [1, 3]]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            ['col_1' => 3],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                0 => ['col_1' => 1],
                1 => ['col_1' => 2],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_between_or_equal_upper()
    {
        $filter = new LogicalFilter(
            ['col_1', '><=', [1, 3]]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            ['col_1' => 3],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                1 => ['col_1' => 2],
                4 => ['col_1' => 3],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_between_or_equal_both()
    {
        $filter = new LogicalFilter(
            ['col_1', '=><=', [1, 3]]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            ['col_1' => 3],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                0 => ['col_1' => 1],
                1 => ['col_1' => 2],
                4 => ['col_1' => 3],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_in()
    {
        $filter = new LogicalFilter(
            ['col_1', 'in', [1, null, 3]]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            ['col_1' => 3],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                0 => ['col_1' => 1],
                2 => ['col_1' => null],
                3 => ['col_2' => 2],
                4 => ['col_1' => 3],
                5 => 2,
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_not_in()
    {
        $filter = new LogicalFilter(
            ['col_1', '!in', [null, 2]]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            ['col_1' => 3],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                0 => ['col_1' => 1],
                4 => ['col_1' => 3],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_operations()
    {
        $filter = new LogicalFilter(
            ['and',
                ['col_1', '<=', 2],
                ['or',
                    ['col_2', '=', 2],
                    ['col_2', '=', 3],
                ],
            ]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2, 'col_2' => 2],
            ['col_1' => null],
            ['col_1' => 1, 'col_2' => 3],
            2,
        ]);

        // print_r($filtered_array);exit;
        $this->assertEquals(
            [
                1 => ['col_1' => 2, 'col_2' => 2],
                3 => ['col_1' => 1, 'col_2' => 3],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_negations()
    {
        $filter = new LogicalFilter(
            ['and',
                ['not',
                    ['col_1', '<', 2]
                ],
                ['or',
                    ['col_2', '=', 2],
                    ['col_2', '=', 3],
                ],
            ]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2, 'col_2' => 2],
            ['col_1' => null],
            ['col_1' => 1, 'col_2' => 3],
            2,
        ]);

        // print_r($filtered_array);exit;
        $this->assertEquals(
            [
                1 => ['col_1' => 2, 'col_2' => 2],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_filter_on_key()
    {
        $filter = new LogicalFilter(
            [key(), '>', 3]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            ['col_1' => 3],
            2,
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                4 => ['col_1' => 3],
                5 => 2,
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_filter_on_value()
    {
        $filter = new LogicalFilter(
            [value()->format('Y-m-d'), '>', '2000-01-10']
        );

        $filtered_array = $filter->applyOn([
            new \DateTime('2000-01-08'),
            new \DateTime('2000-01-09'),
            new \DateTime('2000-01-10'),
            new \DateTime('2000-01-11'),
            new \DateTime('2000-01-12'),
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                3 => new \DateTime('2000-01-11'),
                4 => new \DateTime('2000-01-12'),
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_regexp()
    {
        $filter = new LogicalFilter(
            ['col_1', 'regexp', '/^match_\d$/']
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 'not matching'],
            ['col_1' => 'match_1'],
            ['col_1' => null],
            ['col_2' => 'match_1'],
            ['col_1' => 'match_2'],
            ['col_1' => 8],
            2,
            'match_1',
        ]);

        // print_r($filtered_array); exit;
        $this->assertEquals(
            [
                1 => ['col_1' => 'match_1'],
                4 => ['col_1' => 'match_2'],
            ],
            $filtered_array
        );
    }

    /**
     */
    public function test_regexp_invalid()
    {
        $filter = new LogicalFilter(
            ['col_1', 'regexp', 'not a valid regexp']
        );


        try {
            $filtered_array = $filter->applyOn([
                ['col_1' => 'column value'],
            ]);

            $this->assertTrue(false, "An error must have been thrown here");
        }
        catch (\InvalidArgumentException $e) {
            $this->assertEquals(
                "PCRE error 'preg_match(): Delimiter must not be alphanumeric"
                ." or backslash' while applying the regexp 'not a valid regexp' to 'column value'",
                $e->getMessage()
            );
        }
    }

    /**
     */
    public function test_no_solution()
    {
        $filter = new LogicalFilter(
            ['col_1', '=', 12]
        );

        $filtered_array = $filter->applyOn([
            ['col_1' => 1],
            ['col_1' => 2],
            ['col_1' => null],
            ['col_2' => 2],
            2,
        ]);

        // print_r($filtered_array);exit;
        $this->assertEquals(
            [],
            $filtered_array
        );
    }

    /**/
}
