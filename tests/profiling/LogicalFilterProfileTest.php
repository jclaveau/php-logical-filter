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
                [
                    "adserver_type",
                    "=",
                    "INTERNE"
                ],
                [
                    "adserver_id",
                    ">",
                    0
                ],
                [
                    "adserver_id",
                    ">",
                    12
                ],
                [
                    "adserver_id",
                    ">=",
                    100
                ],
                [
                    "adserver_id",
                    "<",
                    2000
                ],
                [
                    "adserver_id",
                    "=",
                    100
                ],
                [
                    "date",
                    "=",
                    new \DateTime('2018-07-04')
                ],
                [
                    "event",
                    "=",
                    "impression"
                ],
            ]
        ))
        ->simplify()
        // ->dump(true)
        ;

        // var_dump($this->getMemoryUsage());
        $this->assertMemoryUsageBelow('1.1M');

        // var_dump($this->getExecutionTime());
        $this->assertExecutionTimeBelow(0.05);
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
        $this->assertExecutionTimeBelow(0.02);
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
        $this->assertMemoryUsageBelow('7M');

        // var_dump($this->getExecutionTime());
        $this->assertExecutionTimeBelow(1.5);
    }

    /**/
}
