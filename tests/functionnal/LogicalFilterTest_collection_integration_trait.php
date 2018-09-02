<?php
namespace JClaveau\LogicalFilter;
use       DusanKasan\Knapsack\Collection;

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
                [key, '>', 1],
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

    /**/
}
