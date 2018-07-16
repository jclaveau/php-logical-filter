<?php
namespace JClaveau\LogicalFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class LogicalFilterProfileTest extends \AbstractTest
{
    use \JClaveau\PHPUnit\Framework\UsageConstraintTrait;

    /**
     * @profile
     * @coversNothing
     */
    public function test_keepLeafRulesMatching_on_negation()
    {
        // This produced a bug due to comparisons between different fields
        // and missing unset
        $filter = (new LogicalFilter(
            ["and",
                ["type", "=", "my_type"],
                ["id", ">", 0],
                ["id", ">", 12],
                ["id", ">=", 100],
                ["id", "<", 2000],
                ["id", "=", 100],
                ["date", "=", new \DateTime('2018-07-04') ],
                ["event", "=", "somethiong happened"],
            ]
        ))
        ->simplify()
        // ->dump(true)
        ;

        // var_dump($this->getMemoryUsage());
        $this->assertMemoryUsageBelow('1.1M');

        // var_dump($this->getExecutionTime());
        $this->assertExecutionTimeBelow(0.4);
    }

    /**
     * @profile
     * @coversNothing
     */
    public function test_profile_simplify_InRule()
    {
        // This produced a bug due to comparisons between different fields
        // and missing unset
        $filter = (new LogicalFilter(
            ["and",
                ["field", "in", [1, 2, 3, 4, 5, 6]],
                ["field", "in", [5, 6, 7, 8, 8, 10]],
            ]
        ))
        ->simplify([
        ])
        // ->dump(true)
        ;

        // var_dump($this->getMemoryUsage());
        $this->assertMemoryUsageBelow('1');

        // var_dump($this->getExecutionTime());
        $this->assertExecutionTimeBelow(0.1);
    }

    /**
     * @profile
     * @coversNothing
     */
    public function test_profile_simplify_NotInRule()
    {
        // This produced a bug due to comparisons between different fields
        // and missing unset
        $filter = (new LogicalFilter(
            ["and",
                ["field", "in", [1, 2, 3, 4, 5, 6]],
                ["field", "in", [5, 6, 7, 8, 8, 10]],
                ["field2", "!in", [1, 2, 3, 4, 5, 6]],
                ["field2", "!in", [5, 6, 7, 8, 8, 10]],
            ]
        ))
        ->simplify([
        ])
        // ->dump(true)
        ;

        // var_dump($this->getMemoryUsage());
        $this->assertMemoryUsageBelow('1.3M');

        // var_dump($this->getExecutionTime());
        $this->assertExecutionTimeBelow(1.5);
    }

    /**/
}
