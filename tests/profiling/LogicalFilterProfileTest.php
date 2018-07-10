<?php
namespace JClaveau\LogicalFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class LogicalFilterProfileTest extends \AbstractTest
{
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
        ->simplify([
        ])
        // ->dump(true)
        ;


        // $filtered_filter = (new LogicalFilter(
            // ["id", "!in", [299,298]]
        // ))
        // ->keepLeafRulesMatching([
            // 'and',
            // ['field', '=', 'other_field_name'],
        // ])
        // ->dump(true)
        ;

        // $this->assertEquals(
            // null, // TODO this would become TrueRule
            // $filtered_filter->toArray()
        // );
    }

    /**/
}
