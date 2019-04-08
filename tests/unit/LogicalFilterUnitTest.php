<?php
namespace JClaveau\LogicalFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\Rule\EqualRule;

class LogicalFilterUnitTest extends \AbstractTest
{
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
