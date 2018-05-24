<?php
namespace JClaveau\LogicalFilter\Rule;

// use JClaveau\VisibilityViolator\VisibilityViolator;

class AndRuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * + if A = 2 && A > 1 <=> A = 2
     * + if A = 2 && A < 4 <=> A = 2
     */
    public function test_simplify_unifyingDifferendTypesOfOperands()
    {
        $below = new BelowRule('field_name', 3);
        $equal = new EqualRule('field_name', 2);
        $above = new AboveRule('field_name', 1);

        // AndRule
        $this->assertEquals(
            $equal,
            (new AndRule([$below, $equal, $above]))->simplify()
                // ->dump(true)
        );
    }

    /**/
}
