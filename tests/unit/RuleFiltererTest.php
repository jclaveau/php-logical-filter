<?php
namespace JClaveau\LogicalFilter\Filterer;

use JClaveau\VisibilityViolator\VisibilityViolator;
use JClaveau\LogicalFilter\LogicalFilter;

class RuleFiltererTest extends \AbstractTest
{
    /**
     */
    public function test_apply_with_bad_argument()
    {
        try {
            $filtered_rules = (new RuleFilterer)->apply(
                new LogicalFilter(
                    ['or',
                        ['field', '=', 'whatever'],
                    ]
                ),
                'not a rule tree'
            );

            $this->assertTrue(false, "An error must have been thrown here");
        }
        catch (\Exception $e) {
            $this->assertEquals(
                "\$ruleTree_to_filter must be an array or an AbstractRule instead of: 'not a rule tree'",
                $e->getMessage()
            );
        }
    }

    /**/
}
