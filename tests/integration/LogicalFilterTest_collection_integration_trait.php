<?php
namespace JClaveau\LogicalFilter\Tests;

use JClaveau\LogicalFilter\LogicalFilter;
use DusanKasan\Knapsack\Collection;
use function JClaveau\LogicalFilter\key;

trait LogicalFilterTest_collection_integration_trait
{
    /**
     */
    public function test_collection_filter()
    {
        $collection = Collection::from([
            [
                'number' => 5,
                'string' => "lalala",
            ],
            [
                'number' => 7,
                'string' => "ploup",
            ],
            [
                'number' => 7,
                'string' => "ploup",
            ],
        ]);

        $filter = new LogicalFilter(
            ['or',
                ['string', '!=', 'ploup'],
                [key(), '>', 1],
            ]
        );

        $this->assertEquals(
            [
                0 => [
                    'number' => 5,
                    'string' => "lalala",
                ],
                2 => [
                    'number' => 7,
                    'string' => "ploup",
                ],
            ],
            $collection
                ->filter( $filter )
                ->toArray()
        );
    }

    /**
     */
    public function test_collection_as_in_operand()
    {
        $collection = Collection::from([
            5, 6, 7, 8, 9, 10
        ]);

        $matching_rows = (new LogicalFilter(
            ['and',
                ['my_field', 'in', $collection],
                ['my_field', '<', 7],
            ]
        ))
        ->saveAs($filter)
        ->applyOn([
            ['my_field' => 7],
            ['my_field' => 5],
        ])
        ;

        $this->assertEquals(
            ['or',
                ['and',
                    ['my_field', 'in', [5, 6]],
                ],
            ],
            $filter
                // ->dump( true )
                ->toArray()
        );

        $this->assertEquals(
            [
                1 => ['my_field' => 5],
            ],
            $matching_rows
        );
    }

    /**
     */
    public function test_collection_as_notin_operand()
    {
        $collection = Collection::from([
            5, 6, 7, 8, 9, 10
        ]);

        $matching_rows = (new LogicalFilter(
            ['and',
                ['my_field', '!in', $collection],
                ['my_field', '<', 7],
            ]
        ))
        ->saveAs($filter)
        ->applyOn([
            ['my_field' => 7],
            ['my_field' => 3],
        ])
        ;

        $this->assertEquals(
            ['or',
                ['and',
                    ['my_field', '!in', [5, 6]],
                    ['my_field', '<', 7],
                ],
            ],
            $filter
                // ->dump( true )
                ->toArray()
        );

        $this->assertEquals(
            [
                1 => ['my_field' => 3],
            ],
            $matching_rows
        );
    }

    /**/
}
