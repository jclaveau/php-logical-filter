<?php
namespace JClaveau\LogicalFilter;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;
use JClaveau\LogicalFilter\Filterer\Filterer;
use JClaveau\LogicalFilter\Converter\InlineSqlMinimalConverter;

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
        $this->assertMemoryUsageBelow('1.5M');

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

    /**
     * @profile
     * @coversNothing
     */
    public function test_profile_simplify_very_big_in()
    {
        // This produced a bug due to comparisons between different fields
        // and missing unset
        $filter = (new LogicalFilter(
            require __DIR__ . '/LogicalFilterProfileTest_very_big_filter.php'
        ))
        ->simplify([
        ])
        // ->dump(true)
        ;

        // var_dump($this->getMemoryUsage());
        $this->assertMemoryUsageBelow('10M');

        // var_dump($this->getExecutionTime());
        $this->assertExecutionTimeBelow(1.5);
    }

    /**
     * @profile
     * @coversNothing
     */
    public function test_profile_onEachRule_do_not_parse_in_operands()
    {
        $counts = [
            'matches'    => [],
            'mismatches' => [],
        ];

        $filter = (new LogicalFilter(
            require __DIR__ . '/LogicalFilterProfileTest_very_big_filter.php'
        ))
        ->onEachRule(
            ['field', 'in', ['adserver_id', 'campaign_id']],
            [
                Filterer::leaves_only => true,
                Filterer::on_row_matches => function ($rule, $key, &$siblings) use (&$counts) {
                    if (!isset($counts['matches'][$rule::operator]))
                        $counts['matches'][$rule::operator] = 0;

                    $counts['matches'][$rule::operator]++;
                },
                Filterer::on_row_mismatches => function ($rule, $key, &$siblings) use (&$counts) {
                    if (!isset($counts['mismatches'][$rule::operator]))
                        $counts['mismatches'][$rule::operator] = 0;

                    $counts['mismatches'][$rule::operator]++;
                },
            ]
        )
        // ->dump(true)
        ;

        // var_dump($counts);

        $this->assertEquals(22, $counts['matches']['=']);
        $this->assertEquals(22, $counts['matches']['in']);

        // var_dump($this->getMemoryUsage() / 1024 / 1024);
        $this->assertMemoryUsageBelow('5M');

        // var_dump($this->getExecutionTime());
        $this->assertExecutionTimeBelow(0.3);
    }

    /**
     * @profile
     * @coversNothing
     */
    public function test_profile_simplify_reduce_calls()
    {
        $in_rules = [];

        $filter = (new LogicalFilter(
            require __DIR__ . '/LogicalFilterProfileTest_very_big_filter_2.php'
        ))
        ->simplify()
        // ->dump(true)
        ->onEachRule(
            ['operator', '=', 'in'],
            [
                Filterer::leaves_only => true,
                Filterer::on_row_matches => function ($rule, $key, &$siblings) use (&$in_rules) {
                    $in_rules[] = count($rule->getPossibilities());
                },
            ]
        )
        ;

        $this->assertEquals(13344, array_sum($in_rules));

        // var_dump($this->getMemoryUsage() / 1024 / 1024);
        $this->assertMemoryUsageBelow('3.3M');

        // var_dump($this->getExecutionTime());
        $this->assertExecutionTimeBelow(0.26);
    }

    /**/
}
